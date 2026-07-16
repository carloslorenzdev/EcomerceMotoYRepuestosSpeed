<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateMercadoPagoPreferenceAction
{
    /**
     * Create a payment preference in Mercado Pago.
     *
     * @param Order $order
     * @return array{id: string, init_point: string}|null
     */
    public function execute(Order $order): ?array
    {
        $accessToken = config('services.mercadopago.access_token');
        if (!$accessToken) {
            Log::error('Mercado Pago Access Token is not set in config.');
            // Fallback mock preference for local environment testing
            if (config('app.env') === 'local') {
                return [
                    'id' => 'mock_pref_' . str_pad($order->id, 8, '0', STR_PAD_LEFT),
                    'init_point' => route('home') . '?mock_checkout_success=true&order_id=' . $order->id,
                ];
            }
            return null;
        }

        // Map items
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'title' => $item->product ? $item->product->name : 'Repuesto/Servicio',
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->price,
                'currency_id' => 'CLP',
            ];
        }

        $payload = [
            'items' => $items,
            'payer' => [
                'name' => $order->customer_name,
                'email' => $order->customer_email,
            ],
            'back_urls' => [
                'success' => config('services.mercadopago.success_url') 
                    ? str_replace('{order_id}', $order->id, config('services.mercadopago.success_url'))
                    : route('dashboard') . '?payment_status=success&order_id=' . $order->id,
                'failure' => config('services.mercadopago.failure_url') 
                    ? str_replace('{order_id}', $order->id, config('services.mercadopago.failure_url'))
                    : route('dashboard') . '?payment_status=failure&order_id=' . $order->id,
                'pending' => config('services.mercadopago.pending_url') 
                    ? str_replace('{order_id}', $order->id, config('services.mercadopago.pending_url'))
                    : route('dashboard') . '?payment_status=pending&order_id=' . $order->id,
            ],
            'auto_return' => 'approved',
            'notification_url' => route('webhooks.mercadopago'),
            'external_reference' => (string) $order->id,
        ];

        try {
            $response = Http::withToken($accessToken)
                ->post('https://api.mercadopago.com/checkout/preferences', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'id' => $data['id'],
                    'init_point' => $data['init_point'] ?? $data['sandbox_init_point'],
                ];
            }

            Log::error('Mercado Pago Preference API Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Mercado Pago Integration Exception: ' . $e->getMessage());
        }

        return null;
    }
}
