<?php

namespace App\Actions\Webhooks;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProcessMercadoPagoWebhookAction
{
    /**
     * Process a Mercado Pago webhook notification.
     *
     * @param array $payload
     * @return bool
     */
    public function execute(array $payload): bool
    {
        Log::info('Processing Mercado Pago webhook notification: ', $payload);

        // Mercado Pago sends notifications in various formats.
        // Usually, the payment ID is in $payload['data']['id'] or $payload['id'].
        $paymentId = $payload['data']['id'] ?? $payload['id'] ?? null;
        $type = $payload['type'] ?? $payload['topic'] ?? null;

        // We only care about payments
        if (($type !== 'payment' && $type !== 'merchant_order') || !$paymentId) {
            return false;
        }

        $accessToken = config('services.mercadopago.access_token');
        if (!$accessToken) {
            Log::error('Mercado Pago Access Token is not configured.');
            return false;
        }

        try {
            // Fetch payment details from Mercado Pago
            $response = Http::withToken($accessToken)
                ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

            if (!$response->successful()) {
                Log::error("Failed to fetch payment details for ID: {$paymentId}. Response: " . $response->body());
                return false;
            }

            $paymentData = $response->json();
            $orderId = $paymentData['external_reference'] ?? null;
            $status = $paymentData['status'] ?? null; // approved, pending, rejected, cancelled, refunded

            if (!$orderId) {
                Log::warning("Mercado Pago payment {$paymentId} does not have an external_reference.");
                return false;
            }

            $order = Order::find($orderId);
            if (!$order) {
                Log::warning("Order with ID {$orderId} not found for Mercado Pago payment {$paymentId}.");
                return false;
            }

            // Update order based on status
            if ($status === 'approved') {
                DB::transaction(function () use ($order, $paymentId) {
                    // Update order status
                    if ($order->status !== 'paid') {
                        $order->update([
                            'status' => 'paid',
                            'payment_id' => $paymentId,
                        ]);

                        // Deduct product stock
                        foreach ($order->items as $item) {
                            if ($item->product) {
                                $item->product->decrement('stock', $item->quantity);
                            }
                        }
                        Log::info("Order ID {$order->id} updated to 'paid' and stock adjusted.");
                    }
                });
            } elseif (in_array($status, ['rejected', 'cancelled', 'refunded'])) {
                $order->update(['status' => 'failed']);
                Log::info("Order ID {$order->id} updated to 'failed' due to payment status: {$status}");
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Error processing Mercado Pago payment {$paymentId}: " . $e->getMessage());
        }

        return false;
    }
}
