<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.storefront')]
class ProductDetail extends Component
{
    public Product $product;
    public int $quantity = 1;
    public int $activeImageIndex = 0;

    public function mount(string $slug)
    {
        $this->product = Product::where('slug', $slug)->firstOrFail();
    }

    public function increment()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function setActiveImage(int $index)
    {
        if (isset($this->product->visible_images[$index])) {
            $this->activeImageIndex = $index;
        }
    }

    public function addToCart()
    {
        if ($this->product->stock <= 0) return;
        
        $this->dispatch('addToCart', productId: $this->product->id, quantity: $this->quantity);
        
        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Producto agregado al carrito exitosamente.'
        ]);
    }

    public function render()
    {
        // Related products in the same category
        $relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.store.product-detail', [
            'relatedProducts' => $relatedProducts
        ]);
    }
}
