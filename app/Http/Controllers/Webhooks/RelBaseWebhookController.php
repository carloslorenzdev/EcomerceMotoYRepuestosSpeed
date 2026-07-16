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
        Log::info('RelBase Webhook Received', $request->all());

        try {
            $product = $action->execute($request->all());

            if ($product) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product synced successfully',
                    'product_id' => $product->id,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid product payload',
            ], 422);
        } catch (\Exception $e) {
            Log::error('RelBase Webhook Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal server error processing webhook',
            ], 500);
        }
    }
}
