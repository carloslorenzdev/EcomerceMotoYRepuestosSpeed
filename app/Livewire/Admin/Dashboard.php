<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\WebhookLog;

class Dashboard extends Component
{
    /**
     * Render the admin dashboard view.
     */
    public function render()
    {
        // Compute statistics metrics
        $stats = [
            'total_sales' => Order::where('status', 'paid')->sum('total'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('is_active', true)->where('stock', '<=', 5)->count(),
            'total_users' => User::count(),
        ];

        // Fetch recent records
        $recentOrders = Order::latest()->take(5)->get();
        $recentLogs = WebhookLog::latest()->take(5)->get();

        // Calculate sales from the last 7 days
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $carbonDate = now()->subDays($i);
            $dateStr = $carbonDate->format('Y-m-d');
            
            // Short Spanish day names
            $days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            $dayName = $days[$carbonDate->dayOfWeek];

            $dailyTotal = Order::where('status', 'paid')
                ->whereDate('created_at', $dateStr)
                ->sum('total');

            $salesData[] = [
                'day' => $dayName,
                'date' => $carbonDate->format('d/m'),
                'total' => (float)$dailyTotal,
            ];
        }

        // Determine max sales value to scale SVG bar heights properly
        $maxSales = collect($salesData)->max('total') ?: 1000;

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentLogs' => $recentLogs,
            'salesData' => $salesData,
            'maxSales' => $maxSales,
        ])->layout('layouts.admin');
    }
}
