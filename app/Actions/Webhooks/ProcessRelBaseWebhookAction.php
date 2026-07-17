<?php

namespace App\Actions\Webhooks;

use App\Services\RelBaseService;
use Illuminate\Support\Facades\Log;

class ProcessRelBaseWebhookAction
{
    protected RelBaseService $relbaseService;

    public function __construct(RelBaseService $relbaseService)
    {
        $this->relbaseService = $relbaseService;
    }

    /**
     * Process a RelBase webhook payload.
     * Extracts all affected product IDs and syncs them.
     *
     * @param array $payload
     * @param string|null $event
     * @return int Number of products processed
     */
    public function execute(array $payload, ?string $event = null): int
    {
        $productIds = [];

        // 1. Extraer ID del producto si es un evento de actualización directa
        if (isset($payload['product_id'])) {
            $productIds[] = (string) $payload['product_id'];
        } elseif (isset($payload['id']) && str_contains($event ?? '', 'product')) {
            $productIds[] = (string) $payload['id'];
        } elseif (isset($payload['data']['id']) && str_contains($event ?? '', 'product')) {
            $productIds[] = (string) $payload['data']['id'];
        }

        // 2. Extraer IDs si el evento es una venta, factura, boleta, consumo, etc.
        $items = $payload['details'] ?? $payload['data']['details'] ?? $payload['document']['details'] ?? $payload['items'] ?? $payload['data']['items'] ?? [];
        
        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['product_id'])) {
                    $productIds[] = (string) $item['product_id'];
                } elseif (isset($item['producto_id'])) {
                    $productIds[] = (string) $item['producto_id'];
                } elseif (isset($item['id'])) {
                    $productIds[] = (string) $item['id'];
                }
            }
        }

        // Limpiar duplicados y vacíos
        $productIds = array_unique(array_filter($productIds, function ($id) {
            return $id && $id !== 'undefined' && $id !== 'null';
        }));

        if (empty($productIds)) {
            Log::warning("[ProcessRelBaseWebhookAction] No se encontró un ID de producto en el payload para el evento: {$event}");
            return 0;
        }

        $processedCount = 0;

        foreach ($productIds as $productId) {
            Log::info("[ProcessRelBaseWebhookAction] Sincronizando producto afectado por webhook: {$productId}");
            
            // Solicitar al servicio que traiga la "verdad absoluta" desde el API
            $success = $this->relbaseService->syncProductById($productId);
            
            if ($success) {
                $processedCount++;
                Log::info("[ProcessRelBaseWebhookAction] Producto {$productId} sincronizado exitosamente.");
            }
        }

        return $processedCount;
    }
}

