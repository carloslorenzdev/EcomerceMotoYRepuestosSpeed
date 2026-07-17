<?php

namespace App\Http\Controllers\Webhooks;

use App\Actions\Webhooks\ProcessRelBaseWebhookAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RelBaseWebhookController extends Controller
{
    /**
     * Handle the incoming RelBase webhook.
     */
    public function __invoke(Request $request, ProcessRelBaseWebhookAction $action): JsonResponse
    {
        $rawPayload = $request->getContent();
        $event = $request->header('X-Relke-Event') ?? $request->header('x-relke-event');
        $signature = $request->header('X-Relke-Signature') ?? $request->header('x-relke-signature');
        $secret = env('RELBASE_WEBHOOK_SECRET');

        Log::info('[RelBase Webhook] Event Received', ['event' => $event]);

        // 1. Validar Firma Criptográfica (HMAC SHA256)
        if ($secret && $signature && str_starts_with($signature, 'sha256=')) {
            $expectedHash = hash_hmac('sha256', $rawPayload, $secret);
            if ($signature !== 'sha256=' . $expectedHash) {
                Log::warning('[RelBase Webhook] ¡Firma inválida!', [
                    'calculado' => $expectedHash,
                    'recibido' => str_replace('sha256=', '', $signature)
                ]);
            } else {
                Log::info('[RelBase Webhook] Firma verificada correctamente.');
            }
        }

        // 2. Respuesta a Evento de Prueba de RelBase
        if ($event === 'webhook.test') {
            return response()->json(['success' => true, 'message' => 'Test event received']);
        }

        $payload = json_decode($rawPayload, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON'], 400);
        }

        // 3. Ejecutar Acción Principal
        try {
            $processedCount = $action->execute($payload, $event);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'processed_count' => $processedCount,
            ]);

        } catch (\Exception $e) {
            Log::error('[RelBase Webhook] Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal server error processing webhook',
            ], 500);
        }
    }
}

