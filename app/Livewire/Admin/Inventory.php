<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Services\RelBaseService;

class Inventory extends Component
{
    use WithPagination;

    public $search = '';
    public $stockFilter = 'all'; // 'all', 'low', 'out'
    public $editingStocks = []; // Keyed by product ID: [id => stock_value]

    protected $queryString = [
        'search' => ['except' => ''],
        'stockFilter' => ['except' => 'all'],
    ];

    /**
     * Reset pagination on search filter updates.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    /**
     * Update stock level for a product.
     */
    public function updateStock($productId, $newStock)
    {
        $product = Product::findOrFail($productId);
        $newStock = max(0, (int)$newStock);

        $product->update(['stock' => $newStock]);

        // If product is linked to RelBase, push update
        if ($product->relbase_id) {
            $synced = app(RelBaseService::class)->updateProductStock($product->relbase_id, $newStock);
            if ($synced) {
                session()->flash('toast', [
                    'type' => 'success',
                    'message' => "Stock de '{$product->name}' actualizado localmente y sincronizado con RelBase.",
                ]);
            } else {
                session()->flash('toast', [
                    'type' => 'warning',
                    'message' => "Stock de '{$product->name}' actualizado en local, pero falló la sincronización con RelBase.",
                ]);
            }
        } else {
            session()->flash('toast', [
                'type' => 'success',
                'message' => "Stock de '{$product->name}' actualizado localmente.",
            ]);
        }

        // Clear local editing state for this product
        unset($this->editingStocks[$productId]);
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = Product::query()->with('category');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 5);
        } elseif ($this->stockFilter === 'out') {
            $query->where('stock', 0);
        }

        $products = $query->latest()->paginate(15);

        // Prepopulate the local editing array for products on the page
        foreach ($products as $prod) {
            if (!isset($this->editingStocks[$prod->id])) {
                $this->editingStocks[$prod->id] = $prod->stock;
            }
        }

        return view('livewire.admin.inventory', [
            'products' => $products,
        ])->layout('layouts.admin');
    }
}
