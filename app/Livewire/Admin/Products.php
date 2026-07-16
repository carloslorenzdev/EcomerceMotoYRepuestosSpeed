<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Services\RelBaseService;
use Illuminate\Support\Str;

class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $stockFilter = ''; // 'low', 'out', 'in'
    
    // Sync states
    public $isSyncing = false;
    public $syncStatusMessage = '';

    // Modal state for editing
    public $isEditModalOpen = false;
    public $editingProductId = null;
    
    // Form fields
    public $sku = '';
    public $name = '';
    public $description = '';
    public $price = 0;
    public $compare_at_price = null;
    public $stock = 0;
    public $category_id = '';
    public $is_featured = false;
    public $is_active = true;
    public $image_urls = [];
    public $newImageUrl = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'stockFilter' => ['except' => ''],
    ];

    /**
     * Listeners for events
     */
    protected $rules = [
        'sku' => 'required|string',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'compare_at_price' => 'nullable|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Reset pagination on search filter updates.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    /**
     * Toggle visibility of a product.
     */
    public function toggleActive($productId)
    {
        $product = Product::findOrFail($productId);
        $product->is_active = !$product->is_active;
        $product->save();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Estado de visibilidad actualizado.',
        ]);
    }

    /**
     * Toggle featured state of a product.
     */
    public function toggleFeatured($productId)
    {
        $product = Product::findOrFail($productId);
        $product->is_featured = !$product->is_featured;
        $product->save();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Destacado de la tienda actualizado.',
        ]);
    }

    /**
     * Open product editor modal.
     */
    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $this->editingProductId = $product->id;
        
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->compare_at_price = $product->compare_at_price;
        $this->stock = $product->stock;
        $this->category_id = $product->category_id;
        $this->is_featured = $product->is_featured;
        $this->is_active = $product->is_active;
        $this->image_urls = $product->image_url ?? [];
        $this->newImageUrl = '';

        $this->isEditModalOpen = true;
    }

    /**
     * Add image URL helper.
     */
    public function addImageUrl()
    {
        $this->validate([
            'newImageUrl' => 'required|url',
        ], [
            'newImageUrl.url' => 'Debe ser una URL válida.',
            'newImageUrl.required' => 'La URL no puede estar vacía.',
        ]);

        if (!in_array($this->newImageUrl, $this->image_urls)) {
            $this->image_urls[] = $this->newImageUrl;
        }

        $this->newImageUrl = '';
    }

    /**
     * Remove image URL helper.
     */
    public function removeImageUrl($index)
    {
        if (isset($this->image_urls[$index])) {
            unset($this->image_urls[$index]);
            $this->image_urls = array_values($this->image_urls);
        }
    }

    /**
     * Save product details.
     */
    public function saveProduct()
    {
        $this->validate();

        $product = Product::findOrFail($this->editingProductId);
        
        // Check uniqueness of SKU manually to allow custom validation
        $skuConflict = Product::where('sku', $this->sku)
            ->where('id', '!=', $product->id)
            ->exists();

        if ($skuConflict) {
            $this->addError('sku', 'Este SKU ya está registrado por otro producto.');
            return;
        }

        $product->update([
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'image_url' => $this->image_urls,
        ]);

        // Sync stock back to RelBase if configured
        if ($product->relbase_id) {
            app(RelBaseService::class)->updateProductStock($product->relbase_id, $this->stock);
        }

        $this->isEditModalOpen = false;

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Producto guardado exitosamente.',
        ]);
    }

    /**
     * Trigger manual catalog synchronization.
     */
    public function syncFromRelBase()
    {
        $this->isSyncing = true;
        $this->syncStatusMessage = 'Sincronizando con RelBase...';

        try {
            $service = app(RelBaseService::class);
            $result = $service->syncCatalog();

            if ($result['status'] === 'success') {
                session()->flash('toast', [
                    'type' => 'success',
                    'message' => $result['message'],
                ]);
            } else {
                session()->flash('toast', [
                    'type' => 'error',
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            session()->flash('toast', [
                'type' => 'error',
                'message' => 'Excepción durante la sincronización: ' . $e->getMessage(),
            ]);
        }

        $this->isSyncing = false;
        $this->syncStatusMessage = '';
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = Product::query()->with('category');

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Apply stock filter
        if ($this->stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 5);
        } elseif ($this->stockFilter === 'out') {
            $query->where('stock', 0);
        } elseif ($this->stockFilter === 'in') {
            $query->where('stock', '>', 5);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}
