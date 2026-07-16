<?php

namespace App\Actions\Webhooks;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ProcessRelBaseWebhookAction
{
    /**
     * Process a RelBase webhook payload.
     *
     * @param array $payload
     * @return Product|null
     */
    public function execute(array $payload): ?Product
    {
        $event = $payload['event'] ?? 'product.updated';
        $data = $payload['data'] ?? $payload; // support both wrapped and raw data payloads

        // RelBase standard SKU field is 'code'
        $sku = $data['code'] ?? $data['codigo'] ?? $data['sku'] ?? null;
        if (!$sku) {
            return null;
        }

        // Resolve category
        $categoryData = $data['category'] ?? [];
        $categoryName = $categoryData['name'] ?? $data['categoria'] ?? 'Sin Categoría';
        $categorySlug = Str::slug($categoryName);

        $category = Category::firstOrCreate(
            ['slug' => $categorySlug],
            ['name' => $categoryName]
        );

        // Map data fields from RelBase
        $relbaseId = $data['id'] ?? null;
        $name = $data['name'] ?? $data['nombre'] ?? 'Producto Sincronizado';
        $description = $data['description'] ?? $data['descripcion'] ?? null;
        
        // RelBase uses price_sale for final sale price, with fallback to price
        $price = $data['price_sale'] ?? $data['precio'] ?? $data['price'] ?? 0;
        
        // Sum stock from all warehouses in inventories array
        $stock = 0;
        if (isset($data['inventories']) && is_array($data['inventories'])) {
            foreach ($data['inventories'] as $inv) {
                $stock += $inv['stock'] ?? 0;
            }
        } else {
            $stock = $data['stock'] ?? $data['inventory'] ?? 0;
        }

        // Parse images from image object
        $images = [];
        if (isset($data['image']) && is_array($data['image'])) {
            $imageUrl = $data['image']['url'] ?? null;
            if ($imageUrl) {
                $images[] = $imageUrl;
            }
        } elseif (isset($data['imagenes']) && is_array($data['imagenes'])) {
            $images = $data['imagenes'];
        } elseif (isset($data['url_image']) && $data['url_image']) {
            $images = [$data['url_image']];
        }

        // Find product by SKU, or RelBase ID
        $product = Product::where('sku', $sku)
            ->orWhere('relbase_id', $relbaseId)
            ->first();

        if ($product) {
            $product->update([
                'relbase_id' => $relbaseId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image_url' => !empty($product->image_url) ? $product->image_url : $images,
                'category_id' => $category->id,
            ]);
        } else {
            $product = Product::create([
                'relbase_id' => $relbaseId,
                'sku' => $sku,
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(4),
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image_url' => $images,
                'category_id' => $category->id,
                'is_featured' => false,
            ]);
        }

        return $product;
    }
}
