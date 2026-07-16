<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class SearchProductsAction
{
    /**
     * Search and filter products.
     *
     * @param array{
     *     search?: string,
     *     category_slug?: string,
     *     min_price?: float|int,
     *     max_price?: float|int,
     *     sort_by?: string
     * } $filters
     * @return Builder
     */
    public function execute(array $filters = []): Builder
    {
        $query = Product::query()->with('category');

        // Text search (SKU, Name, Description)
        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('sku', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        // Category filter
        if (!empty($filters['category_slug'])) {
            $query->whereHas('category', function (Builder $q) use ($filters) {
                $q->where('slug', $filters['category_slug']);
            });
        }

        // Price range
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'latest';
        match ($sortBy) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'stock_desc' => $query->orderBy('stock', 'desc'),
            default => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
        };

        return $query;
    }
}
