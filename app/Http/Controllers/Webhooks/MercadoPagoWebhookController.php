<?php

namespace App\Http\Controllers\Webhooks;

use App\Actions\Webhooks\ProcessMercadoPagoWebhookAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    /**
     * Handle the incoming Mercado Pago webhook.
     */
    public function __invoke(Request $request, ProcessMercadoPagoWebhookAction $action): JsonResponse
    {
        Log::info('Mercado Pago Webhook Received', $request->all());

        // Mercado Pago expects a 200 OK response quickly.
        // We trigger the processing logic and log errors if it fails, but always return success.
        try {
            $success = $action->execute($request->all());
            
            return response()->json([
                'received' => true,
                'processed' => $success,
            ]);
        } catch (\Exception $e) {
            Log::error('Mercado Pago Webhook Error: ' . $e->getMessage());
            
            // Still return 200 to prevent MP from repeatedly hammering the server
            return response()->json([
                'received' => true,
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
