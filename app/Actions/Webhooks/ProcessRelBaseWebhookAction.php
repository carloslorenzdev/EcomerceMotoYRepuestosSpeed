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
        // Función recursiva para buscar cualquier llave que parezca un ID de producto o SKU en el JSON
        $productIds = [];
        $skus = [];
        $extractIds = function ($array) use (&$extractIds, &$productIds, &$skus, $event) {
            if (!is_array($array)) return;
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    // Si es un nodo de producto, extraer su ID y SKU
                    if (in_array($key, ['product', 'producto', 'item'])) {
                        if (isset($value['id'])) $productIds[] = (string) $value['id'];
                        if (isset($value['sku'])) $skus[] = (string) $value['sku'];
                        if (isset($value['code'])) $skus[] = (string) $value['code'];
                        if (isset($value['codigo'])) $skus[] = (string) $value['codigo'];
                    }
                    $extractIds($value);
                } else {
                    if (in_array($key, ['product_id', 'producto_id'])) {
                        $productIds[] = (string) $value;
                    } elseif ($key === 'id' && (str_contains(strtolower($event ?? ''), 'product') || str_contains(strtolower($event ?? ''), 'producto'))) {
                        $productIds[] = (string) $value;
                    } elseif (in_array($key, ['sku', 'code', 'codigo', 'product_sku'])) {
                        $skus[] = (string) $value;
                    }
                }
            }
        };

        $extractIds($payload);

        // Convertir SKUs encontrados a IDs de producto de RelBase buscando en nuestra BD local
        if (!empty($skus)) {
            $skus = array_unique(array_filter($skus));
            $mappedIds = \App\Models\Product::whereIn('sku', $skus)->whereNotNull('relbase_id')->pluck('relbase_id')->toArray();
            $productIds = array_merge($productIds, $mappedIds);
            if (!empty($mappedIds)) {
                Log::info("[ProcessRelBaseWebhookAction] Se encontraron SKUs en el payload que fueron mapeados a IDs de RelBase: " . implode(', ', $mappedIds));
            }
        }

        // Limpiar duplicados y vacíos
        $productIds = array_unique(array_filter($productIds, function ($id) {
            return !empty($id) && $id !== 'undefined' && $id !== 'null';
        }));

        if (empty($productIds)) {
            Log::warning("[ProcessRelBaseWebhookAction] No se encontró un ID de producto en el payload para el evento: {$event}. Payload: " . json_encode($payload));
            
            // FALLBACK: Como no pudimos extraer el ID de la respuesta, 
            // forzamos una sincronización completa en segundo plano de manera preventiva.
            if (str_contains(strtolower($event ?? ''), 'inventory') || str_contains(strtolower($event ?? ''), 'stock')) {
                Log::info("[ProcessRelBaseWebhookAction] Disparando sincronización completa de emergencia por evento de inventario ciego.");
                
                // Ejecutar el comando de sincronización en segundo plano
                $command = 'php ' . base_path('artisan') . ' relbase:sync > /dev/null 2>&1 &';
                exec($command);
                
                return 1; // Retornamos 1 para indicar que sí se tomó acción
            }
            
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

