<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Confirmación de compra</title>
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
                            <h1 style="margin:0 0 6px;font-size:20px;color:#0f172a;">Compra confirmada</h1>
                            <p style="margin:0 0 16px;font-size:14px;color:#334155;">Hola {{ $order->customer_name }}, gracias por tu compra.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e2e8f0;border-radius:12px;">
                                <tr>
                                    <td style="padding:16px;">
                                        <div style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;">Resumen del pedido</div>
                                        <div style="margin-top:8px;font-size:14px;color:#0f172a;"><strong>Pedido:</strong> #{{ $order->id }}</div>
                                        <div style="margin-top:4px;font-size:14px;color:#0f172a;"><strong>Total:</strong> ${{ number_format($order->total, 0, ',', '.') }} CLP</div>
                                    </td>
                                </tr>
                            </table>

                            @if($order->items && count($order->items) > 0)
                                <div style="margin-top:16px;">
                                    <div style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;">Artículos comprados</div>
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

                            @php
                                $address = $order->shipping_address;
                                $hasAddress = !empty($address) && !empty($address['street']);
                            @endphp

                            <div style="margin-top:16px;padding:12px 14px;border-radius:12px;background:{{ $hasAddress ? '#ecfdf3' : '#fff7ed' }};border:1px solid {{ $hasAddress ? '#bbf7d0' : '#fed7aa' }};color:{{ $hasAddress ? '#166534' : '#9a3412' }};font-size:13px;">
                                @if($hasAddress)
                                    <strong>Dirección de envío:</strong> {{ $address['street'] }}, Comuna de {{ $address['commune'] ?? '' }}, {{ $address['city'] ?? '' }}
                                @else
                                    Aún falta tu dirección de envío. Completa este paso para poder despachar.
                                @endif
                            </div>

                            @if(!$hasAddress)
                                <div style="margin-top:16px;">
                                    <a href="{{ url('/dashboard') }}" style="display:inline-block;background:#dc2626;color:#ffffff;text-decoration:none;padding:12px 16px;border-radius:10px;font-weight:700;font-size:13px;">
                                        Completar dirección de envío
                                    </a>
                                </div>
                            @endif

                            <p style="margin:18px 0 0;font-size:13px;color:#475569;">Te avisaremos por este medio cuando tu pedido sea procesado, enviado y entregado.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 24px;border-top:1px solid #e2e8f0;background:#f8fafc;font-size:12px;color:#64748b;">
                            Si no reconoces esta compra, responde a este correo.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
