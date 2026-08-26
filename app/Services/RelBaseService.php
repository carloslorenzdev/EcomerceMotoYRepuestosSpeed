<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RelBaseService
{
    /**
     * Get a valid OAuth Access Token from Cache or refresh it.
     */
    public function getValidToken(): ?string
    {
        $token = Cache::get('relbase_access_token');
        if ($token) {
            return $token;
        }

        $clientId = env('RELBASE_CLIENT_ID');
        $clientSecret = env('RELBASE_CLIENT_SECRET');
        $refreshToken = env('RELBASE_REFRESH_TOKEN');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            Log::error('[RelBaseService] Missing credentials in env');
            return null;
        }

        try {
            $response = Http::asForm()->post('https://api.relbase.cl/oauth/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
            ]);

            if ($response->failed()) {
                Log::error('[RelBaseService] Error refreshing token: ' . $response->body());
                return null;
            }

            $data = $response->json();
            $accessToken = $data['access_token'] ?? null;
            if ($accessToken) {
                // Cache the token for 14 minutes (Expires in 15 mins)
                Cache::put('relbase_access_token', $accessToken, 840);
                return $accessToken;
            }
        } catch (\Exception $e) {
            Log::error('[RelBaseService] Exception refreshing token: ' . $e->getMessage());
        }

        return null;
    }

    private function downloadRelbaseImage(string $url, string $sku): string
    {
        try {
            $contents = Http::timeout(10)->get($url)->body();
            if (empty($contents)) return $url;
            
            $filename = 'products/rb_' . $sku . '_' . md5($url) . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
            return asset('storage/' . $filename);
        } catch (\Exception $e) {
            Log::error("[RelBaseService] Error downloading image for SKU {$sku}: " . $e->getMessage());
            return $url;
        }
    }

    /**
     * Download and sync the entire product catalog from RelBase.
     */
    public function syncCatalog(callable $progressCallback = null): array
    {
        $accessToken = $this->getValidToken();
        if (!$accessToken) {
            return ['status' => 'error', 'message' => 'No se pudo obtener el token de acceso de RelBase.'];
        }

        $page = 1;
        $totalPages = 1;
        $allProducts = [];

        if ($progressCallback) {
            $progressCallback('running', 'Descargando productos desde RelBase...', 10);
        }

        while ($page <= $totalPages) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                    'Accept' => 'application/json',
                ])->get("https://api.relbase.cl/api/v2/productos", [
                    'page' => $page,
                    'per_page' => 50,
                ]);

                if ($response->failed()) {
                    return ['status' => 'error', 'message' => "Error en API RelBase (Página {$page}): " . $response->body()];
                }

                $data = $response->json();
                $products = $data['data']['products'] ?? [];
                $allProducts = array_merge($allProducts, $products);

                $totalPages = $data['meta']['total_pages'] ?? 1;
                
                if ($progressCallback) {
                    $pct = min(90, 10 + round(($page / $totalPages) * 70));
                    $progressCallback('running', "Descargando página {$page} de {$totalPages}...", $pct);
                }
                
                $page++;
            } catch (\Exception $e) {
                return ['status' => 'error', 'message' => "Error de red al descargar catálogo (Página {$page}): " . $e->getMessage()];
            }
        }

        $inserted = 0;

        if ($progressCallback) {
            $progressCallback('running', 'Procesando e insertando productos en la base de datos...', 85);
        }

        foreach ($allProducts as $p) {
            if (empty($p['name'])) continue;
            if (($p['enabled'] ?? true) !== true) continue; // Solo productos vigentes

            $stock = 0;
            if (!empty($p['inventories']) && is_array($p['inventories'])) {
                foreach ($p['inventories'] as $inv) {
                    $stock += $inv['stock'] ?? 0;
                }
            }

            $nameLower = strtolower($p['name']);
            if (str_contains($nameLower, 'servicio') || $nameLower === 'abono') {
                continue; 
            }

            $categoryName = $p['category']['name'] ?? 'Sin Categoría';
            $categorySlug = Str::slug($categoryName);
            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                ['name' => $categoryName, 'description' => "Categoría importada: {$categoryName}"]
            );

            $price = $p['is_tax_affected'] ? round(($p['price'] ?? 0) * 1.19) : round($p['price'] ?? 0);
            $comparePrice = !empty($p['price_sale']) ? ($p['is_tax_affected'] ? round($p['price_sale'] * 1.19) : round($p['price_sale'])) : null;

            $sku = $p['code'] ?? null;
            if (!$sku) continue;

            $localProduct = Product::where('sku', $sku)->orWhere('relbase_id', $p['id'])->first();

            $slug = Str::slug($p['name']);
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('sku', '!=', $sku)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }

            // Evaluar si debemos conservar la imagen local o descargar una nueva
            $preserveImage = false;
            if ($localProduct && !empty($localProduct->image_url)) {
                $firstImage = $localProduct->image_url[0] ?? '';
                // Si la imagen guardada es un enlace temporal de Amazon S3, está rota, NO la conserves.
                if (!str_contains($firstImage, 'X-Amz-Expires') && !str_contains($firstImage, 'amazonaws.com')) {
                    $preserveImage = true;
                }
            }

            $images = [];
            if ($preserveImage) {
                $images = $localProduct->image_url;
            } else {
                $relbaseImageUrl = $p['url_image'] ?? ($p['image']['url'] ?? null);
                if ($relbaseImageUrl) {
                    // Descargar físicamente la imagen al servidor
                    $downloadedUrl = $this->downloadRelbaseImage($relbaseImageUrl, $sku);
                    $images[] = $downloadedUrl;
                }
            }

            if ($localProduct) {
                $localProduct->update([
                    'relbase_id' => $p['id'],
                    'name' => $p['name'],
                    'description' => $localProduct->description ?: ($p['description'] ?? ''),
                    'price' => $price,
                    'compare_at_price' => $comparePrice,
                    'stock' => max(0, (int)$stock),
                    'image_url' => $images,
                    'category_id' => $category->id,
                ]);
            } else {
                Product::create([
                    'relbase_id' => $p['id'],
                    'sku' => $sku,
                    'name' => $p['name'],
                    'slug' => $slug,
                    'description' => $p['description'] ?? '',
                    'price' => $price,
                    'compare_at_price' => $comparePrice,
                    'stock' => max(0, (int)$stock),
                    'image_url' => $images,
                    'category_id' => $category->id,
                    'is_featured' => false,
                    'is_active' => true,
                ]);
            }

            $inserted++;
        }

        if ($progressCallback) {
            $progressCallback('success', "Sincronización completada con éxito. {$inserted} productos actualizados.", 100);
        }

        return ['status' => 'success', 'message' => "Sincronización completada. {$inserted} productos actualizados."];
    }

    /**
     * Fetch a single product from RelBase and sync it locally.
     */
    public function syncProductById(string $relbaseId): bool
    {
        $accessToken = $this->getValidToken();
        if (!$accessToken) {
            Log::error("[RelBaseService] Missing token to sync product $relbaseId");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ])->get("https://api.relbase.cl/api/v2/productos/{$relbaseId}");

            if ($response->failed()) {
                Log::warning("[RelBaseService] Product {$relbaseId} not found in API on webhook sync.");
                return false;
            }

            $data = $response->json();
            $p = $data['data'] ?? null;
            if (!$p) return false;

            // Resolve Category
            $categoryName = $p['category']['name'] ?? 'Sin Categoría';
            $categorySlug = Str::slug($categoryName);
            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                ['name' => $categoryName, 'description' => "Categoría importada: {$categoryName}"]
            );

            // Compute Stock
            $stock = 0;
            if (!empty($p['inventories']) && is_array($p['inventories'])) {
                foreach ($p['inventories'] as $inv) {
                    $stock += $inv['stock'] ?? 0;
                }
            }

            // Compute Price
            $price = $p['is_tax_affected'] ? round(($p['price'] ?? 0) * 1.19) : round($p['price'] ?? 0);
            $comparePrice = !empty($p['price_sale']) ? ($p['is_tax_affected'] ? round($p['price_sale'] * 1.19) : round($p['price_sale'])) : null;

            // Resolve Images
            $images = [];
            $relbaseImageUrl = $p['url_image'] ?? ($p['image']['url'] ?? null);
            if ($relbaseImageUrl) {
                $images[] = $relbaseImageUrl;
            }

            $sku = $p['code'] ?? null;
            if (!$sku) return false;

            $localProduct = Product::where('sku', $sku)
                ->orWhere('relbase_id', $p['id'])
                ->first();

            $slug = Str::slug($p['name']);
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('sku', '!=', $sku)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }

            if ($localProduct) {
                $preserveImage = !empty($localProduct->image_url);
                $localProduct->update([
                    'relbase_id' => $p['id'],
                    'name' => $p['name'],
                    'description' => $localProduct->description ?: ($p['description'] ?? ''),
                    'price' => $price,
                    'compare_at_price' => $comparePrice,
                    'stock' => max(0, (int)$stock),
                    'image_url' => $preserveImage ? $localProduct->image_url : $images,
                    'category_id' => $category->id,
                    'is_active' => $p['enabled'] ?? true,
                ]);
            } else {
                Product::create([
                    'relbase_id' => $p['id'],
                    'sku' => $sku,
                    'name' => $p['name'],
                    'slug' => $slug,
                    'description' => $p['description'] ?? '',
                    'price' => $price,
                    'compare_at_price' => $comparePrice,
                    'stock' => max(0, (int)$stock),
                    'image_url' => $images,
                    'category_id' => $category->id,
                    'is_featured' => false,
                    'is_active' => $p['enabled'] ?? true,
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("[RelBaseService] Exception syncing product {$relbaseId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update stock level for a product directly in RelBase.
     */
    public function updateProductStock(string $relbaseId, int $newStock): bool
    {
        $token = $this->getValidToken();
        if (!$token) return false;

        try {
            // Get current product state from RelBase first
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'Accept' => 'application/json',
            ])->get("https://api.relbase.cl/api/v2/productos/{$relbaseId}");

            if ($response->failed()) {
                Log::error("[RelBaseService] Error fetching product {$relbaseId} for stock update: " . $response->status());
                return false;
            }

            $data = $response->json();
            $p = $data['data'] ?? null;
            if (!$p || empty($p['inventories'])) return false;

            // Prepare payload updating stock in the primary inventory location, preserving ALL original data
            $payload = $p;
            $payload['inventories'][0]['stock'] = $newStock;

            // Send PUT update
            $putResponse = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->put("https://api.relbase.cl/api/v2/productos/{$relbaseId}", $payload);

            if ($putResponse->failed()) {
                Log::error("[RelBaseService] Error updating product stock in RelBase: " . $putResponse->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("[RelBaseService] Exception updating stock: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Decrement stock levels in RelBase based on completed order items.
     */
    public function decrementStock(Order $order): void
    {
        $token = $this->getValidToken();
        if (!$token) {
            Log::error("[RelBaseService] No valid token to decrement stock.");
            return;
        }

        foreach ($order->items as $item) {
            $product = $item->product;
            if (!$product || !$product->relbase_id) continue;

            try {
                // Fetch product from RelBase to get inventories layout
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ])->get("https://api.relbase.cl/api/v2/productos/{$product->relbase_id}");

                if ($response->failed()) continue;

                $data = $response->json();
                $p = $data['data'] ?? null;
                if (!$p || empty($p['inventories'])) continue;

                $currentStock = (int)($p['inventories'][0]['stock'] ?? 0);
                $newStock = max(0, $currentStock - $item->quantity);

                // Update stock
                $this->updateProductStock($product->relbase_id, $newStock);
            } catch (\Exception $e) {
                Log::error("[RelBaseService] Exception decrementing stock for item: " . $e->getMessage());
            }
        }
    }
}
