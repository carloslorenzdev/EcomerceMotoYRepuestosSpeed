<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\Service;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.storefront')]
class Home extends Component
{
    public $featuredProducts = [];
    public $services = [];

    public function mount(): void
    {
        $this->featuredProducts = Product::where('is_featured', true)
            ->take(8)
            ->get();

        $this->services = Service::where('is_active', true)
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.store.home');
    }
}
