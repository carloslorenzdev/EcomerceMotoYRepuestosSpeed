<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Estado de tu pedido</title>
</head>
<body style="margin:0;background:#f4f5f7;font-family:Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f5f7;padding:24px 0;">
        <tr>
            <td align="center" style="padding:0 16px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:#0f172a;color:#ffffff;padding:20px 24px;">
                            <div style="font-size:16px;font-weight:700;letter-spacing:0.5px;">MotoSpeed</div>
                            <div style="font-size:12px;color:#cbd5f5;">Repuestos y Accesorios</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            @php
                                $statusTitles = [
                                    'pendiente' => 'Pedido Registrado',
                                    'procesando' => 'Pedido en Preparación',
                                    'enviado' => 'Pedido Enviado',
                                    'entregado' => 'Pedido Entregado',
                                ];
                                $statusBodies = [
                                    'pendiente' => 'Tu pedido ha sido registrado con éxito. Estamos a la espera de completar tu dirección o pago.',
                                    'procesando' => 'Estamos preparando tu pedido para enviarlo lo antes posible.',
                                    'enviado' => 'Tu pedido ya fue despachado. Te avisaremos cuando sea entregado.',
                                    'entregado' => 'Tu pedido fue entregado. Esperamos que disfrutes de tu compra.',
                                ];
                                $title = $statusTitles[$order->shipping_status] ?? 'Actualización del Pedido';
                                $body = $statusBodies[$order->shipping_status] ?? 'Tu pedido ha cambiado de estado.';
                            @endphp

                            <h1 style="margin:0 0 6px;font-size:20px;color:#0f172a;">{{ $title }}</h1>
                            <p style="margin:0 0 16px;font-size:14px;color:#334155;">Hola {{ $order->customer_name }}, {{ $body }}</p>

                            @if($order->shipping_status === 'enviado' && $order->shipping_tracking_number)
                                <div style="margin-bottom:16px;padding:12px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;color:#0369a1;font-size:13px;">
                                    <strong>Código de Seguimiento:</strong> {{ $order->shipping_tracking_number }}
                                </div>
                            @endif

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e2e8f0;border-radius:12px;">
                                <tr>
                                    <td style="padding:16px;">
                                        <div style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;">Detalle del pedido</div>
                                        <div style="margin-top:8px;font-size:14px;color:#0f172a;"><strong>Pedido:</strong> #{{ $order->id }}</div>
                                        <div style="margin-top:4px;font-size:14px;color:#0f172a;"><strong>Total:</strong> ${{ number_format($order->total, 0, ',', '.') }} CLP</div>
                                    </td>
                                </tr>
                            </table>

                            @if($order->items && count($order->items) > 0)
                                <div style="margin-top:16px;">
                                    <div style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;">Artículos</div>
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:6px;border-top:1px solid #e2e8f0;">
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;color:#0f172a;font-size:13px;">
                                                    {{ $item->product ? $item->product->name : 'Repuesto/Servicio' }}
                                                </td>
                                                <td align="right" style="padding:8px 0;border-bottom:1px solid #e2e8f0;color:#64748b;font-size:12px;">
                                                    x{{ $item->quantity }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @endif

                            <p style="margin:18px 0 0;font-size:13px;color:#475569;">Seguiremos informándote sobre tu compra.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 24px;border-top:1px solid #e2e8f0;background:#f8fafc;font-size:12px;color:#64748b;">
                            Gracias por comprar en MotoSpeed.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
