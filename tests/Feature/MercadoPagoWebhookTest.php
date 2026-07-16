<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MercadoPagoWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set mock access token in config
        config(['services.mercadopago.access_token' => 'mock_token']);
    }

    /**
     * Test payment approved hook updates order and decrements stock.
     */
    public function test_approved_payment_updates_order_status_and_stock(): void
    {
        $category = Category::create([
            'name' => 'Frenos',
            'slug' => 'frenos',
        ]);

        $product = Product::create([
            'sku' => 'PAST-001',
            'name' => 'Pastillas de freno Brembo',
            'slug' => 'pastillas-de-freno-brembo',
            'price' => 15000,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $order = Order::create([
            'status' => 'pending',
            'total' => 15000,
            'payment_gateway' => 'mercado_pago',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 15000,
        ]);

        // Mock Mercado Pago payments details API request
        Http::fake([
            'https://api.mercadopago.com/v1/payments/*' => Http::response([
                'id' => 999888777,
                'status' => 'approved',
                'external_reference' => (string) $order->id,
                'transaction_amount' => 15000,
            ], 200)
        ]);

        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => '999888777'
            ]
        ];

        $response = $this->postJson(route('webhooks.mercadopago'), $payload);

        $response->assertStatus(200);

        // Verify order status updated to paid
        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertEquals('999888777', $order->fresh()->payment_id);

        // Verify stock was decremented (10 - 2 = 8)
        $this->assertEquals(8, $product->fresh()->stock);
    }
}
