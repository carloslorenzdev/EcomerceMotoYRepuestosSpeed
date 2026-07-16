<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $paymentStatusFilter = '';
    public $shippingStatusFilter = '';

    // Modal state
    public $isDetailModalOpen = false;
    public $selectedOrderId = null;

    // Form fields for shipping updates
    public $shipping_status = 'pendiente';
    public $shipping_tracking_number = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'paymentStatusFilter' => ['except' => ''],
        'shippingStatusFilter' => ['except' => ''],
    ];

    /**
     * Reset pagination on search filter updates.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingShippingStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * View detailed order information.
     */
    public function viewOrderDetails($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        $this->selectedOrderId = $order->id;
        $this->shipping_status = $order->shipping_status ?? 'pendiente';
        $this->shipping_tracking_number = $order->shipping_tracking_number ?? '';
        
        $this->isDetailModalOpen = true;
    }

    /**
     * Quick status update.
     */
    public function updateShippingStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        
        $updateData = ['shipping_status' => $status];
        if ($status !== 'enviado' && $status !== 'entregado') {
            $updateData['shipping_tracking_number'] = null;
            $this->shipping_tracking_number = '';
        }

        $order->update($updateData);

        // Send email status update
        try {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)
                ->send(new \App\Mail\OrderStatusMail($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send order status email for Order #{$order->id}: " . $e->getMessage());
        }

        if ($this->selectedOrderId == $orderId) {
            $this->shipping_status = $status;
        }

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Estado del pedido actualizado a "' . ucfirst($status) . '" exitosamente.',
        ]);
    }

    /**
     * Mark order as shipped.
     */
    public function markAsShipped($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update([
            'shipping_status' => 'enviado',
            'shipping_tracking_number' => $this->shipping_tracking_number,
        ]);

        // Send email status update
        try {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)
                ->send(new \App\Mail\OrderStatusMail($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send order status email for Order #{$order->id}: " . $e->getMessage());
        }

        if ($this->selectedOrderId == $orderId) {
            $this->shipping_status = 'enviado';
        }

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'El pedido fue marcado como Enviado con seguimiento: ' . $this->shipping_tracking_number,
        ]);
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = Order::query()->with('items.product');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                  ->orWhere('payment_id', 'like', '%' . $this->search . '%')
                  ->orWhere('id', $this->search);
            });
        }

        if ($this->paymentStatusFilter) {
            $query->where('status', $this->paymentStatusFilter);
        }

        if ($this->shippingStatusFilter) {
            $query->where('shipping_status', $this->shippingStatusFilter);
        }

        $orders = $query->latest()->paginate(10);
        $selectedOrder = $this->selectedOrderId ? Order::with('items.product')->find($this->selectedOrderId) : null;

        return view('livewire.admin.orders', [
            'orders' => $orders,
            'selectedOrder' => $selectedOrder,
        ])->layout('layouts.admin');
    }
}
