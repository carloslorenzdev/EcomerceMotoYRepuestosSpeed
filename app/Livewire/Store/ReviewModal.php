<?php

namespace App\Livewire\Store;

use App\Models\Review;
use App\Models\Order;
use Livewire\Component;

class ReviewModal extends Component
{
    public bool $isOpen = false;
    public string $customer_name = '';
    public string $customer_email = '';
    public int $rating = 5;
    public string $comment = '';
    public ?int $orderId = null;
    public bool $submitted = false;

    /**
     * Component mount.
     */
    public function mount()
    {
        // Detect success return params
        $isSuccess = request()->query('payment_status') === 'success' || request()->query('mock_checkout_success') === 'true';
        $orderIdParam = request()->query('order_id');

        if ($isSuccess && $orderIdParam) {
            $order = Order::find($orderIdParam);
            if ($order) {
                $this->customer_name = $order->customer_name;
                $this->customer_email = $order->customer_email;
                $this->orderId = $order->id;
                $this->isOpen = true;
            }
        }
    }

    /**
     * Submit review.
     */
    public function submitReview()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'order_id' => $this->orderId,
            'is_approved' => true,
        ]);

        $this->submitted = true;
        $this->dispatch('toast', variant: 'success', text: '¡Muchas gracias por tu opinión!');
    }

    /**
     * Close modal.
     */
    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.store.review-modal');
    }
}
