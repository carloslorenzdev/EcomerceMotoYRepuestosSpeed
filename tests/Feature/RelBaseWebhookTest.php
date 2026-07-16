<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelBaseWebhookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a new product is created from RelBase webhook payload.
     */
    public function test_product_is_created_via_webhook(): void
    {
        $payload = [
            'event' => 'product.updated',
            'id' => 7049624,
            'name' => 'Cilindro Bajaj Pulsar 200',
            'code' => 'PULSAR-CYL',
            'description' => 'Cilindro original de recambio.',
            'price_sale' => 45000,
            'image' => [
                'url' => 'http://example.com/cylinder.jpg',
            ],
            'category' => [
                'id' => 95864,
                'name' => 'Motor Pulsar'
            ],
            'inventories' => [
                [
                    'stock' => 12
                ]
            ]
        ];

        $response = $this->postJson(route('webhooks.relbase'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product synced successfully',
            ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'PULSAR-CYL',
            'relbase_id' => '7049624',
            'name' => 'Cilindro Bajaj Pulsar 200',
            'price' => 45000.00,
            'stock' => 12,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Motor Pulsar',
            'slug' => 'motor-pulsar',
        ]);
    }
}
