<?php

namespace App\Livewire\Store;

use Livewire\Component;
use Livewire\Attributes\On;

class CartCount extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->updateCount();
    }

    #[On('cartUpdated')]
    public function updateCount(): void
    {
        $cart = session()->get('cart', []);
        $this->count = array_reduce($cart, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);
    }

    public function render()
    {
        return view('livewire.store.cart-count');
    }
}
