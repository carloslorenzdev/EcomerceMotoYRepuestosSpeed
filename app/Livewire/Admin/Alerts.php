<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Services\RelBaseService;

class Alerts extends Component
{
    use WithPagination;

    public $search = '';
    public $editingStocks = [];

    /**
     * Reset pagination on search filter updates.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Update stock level inline.
     */
    public function updateStock($productId, $newStock)
    {
        $product = Product::findOrFail($productId);
        $newStock = max(0, (int)$newStock);
        
        $product->update(['stock' => $newStock]);

        if ($product->relbase_id) {
            $synced = app(RelBaseService::class)->updateProductStock($product->relbase_id, $newStock);
            if ($synced) {
                session()->flash('toast', [
                    'type' => 'success',
                    'message' => "Stock de '{$product->name}' actualizado y sincronizado con RelBase.",
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

        unset($this->editingStocks[$productId]);
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = Product::query()->with('category')
            ->where('stock', '<=', 5);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        $products = $query->latest()->paginate(15);

        foreach ($products as $prod) {
            if (!isset($this->editingStocks[$prod->id])) {
                $this->editingStocks[$prod->id] = $prod->stock;
            }
        }

        return view('livewire.admin.alerts', [
            'products' => $products,
        ])->layout('layouts.admin');
    }
}
