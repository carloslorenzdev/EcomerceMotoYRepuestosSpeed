<?php

namespace App\Livewire\Store;

use App\Actions\Products\SearchProductsAction;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;

#[Layout('layouts.storefront')]
class Shop extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $categorySlug = '';

    #[Url(history: true)]
    public string $sortBy = 'latest';

    public string $minPrice = '';
    public string $maxPrice = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategorySlug(): void
    {
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function filterCategory(string $slug): void
    {
        $this->categorySlug = $this->categorySlug === $slug ? '' : $slug;
        $this->resetPage();
    }

    public function applyPriceFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'categorySlug', 'minPrice', 'maxPrice', 'sortBy']);
        $this->resetPage();
    }

    public function render(SearchProductsAction $searchAction)
    {
        $categories = Category::withCount('products')->get();

        $products = $searchAction->execute([
            'search' => $this->search,
            'category_slug' => $this->categorySlug,
            'min_price' => $this->minPrice !== '' ? (float) $this->minPrice : null,
            'max_price' => $this->maxPrice !== '' ? (float) $this->maxPrice : null,
            'sort_by' => $this->sortBy,
        ])->paginate(9);

        return view('livewire.store.shop', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
