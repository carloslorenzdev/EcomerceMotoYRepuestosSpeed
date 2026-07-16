<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{
    /**
     * Compile statistical indicators.
     */
    public function render()
    {
        // Monthly sales data (Current Year)
        $monthlySales = [];
        $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        for ($m = 1; $m <= 12; $m++) {
            $totalSales = Order::where('status', 'paid')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $m)
                ->sum('total');

            $monthlySales[] = [
                'month' => $months[$m - 1],
                'total' => (float)$totalSales,
            ];
        }

        $maxMonthlySales = collect($monthlySales)->max('total') ?: 1000;

        // Sales distribution by category
        $categorySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'paid')
            ->select('categories.name as category_name', DB::raw('SUM(order_items.price * order_items.quantity) as total_sales'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        // Top products by quantity sold
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'paid')
            ->select('products.name', 'products.sku', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.price * order_items.quantity) as total_val'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('livewire.admin.reports', [
            'monthlySales' => $monthlySales,
            'maxMonthlySales' => $maxMonthlySales,
            'categorySales' => $categorySales,
            'topProducts' => $topProducts,
        ])->layout('layouts.admin');
    }
}
