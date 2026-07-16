<x-layouts::client :title="'Mi Panel'">
    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Welcome header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight font-heading">Mis Pedidos</h1>
            <p class="text-sm text-gray-500 dark:text-neutral-400 mt-1">
                Hola, <span class="font-bold text-gray-800 dark:text-neutral-200">{{ auth()->user()->name }}</span>. Aquí puedes realizar el seguimiento de tus compras y envíos.
            </p>
        </div>

        <!-- Success Toast/Banner from Mercado Pago Checkout Return -->
        @if(request()->get('payment_status') === 'success')
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/30 text-emerald-800 dark:text-emerald-400 flex items-start gap-3.5 shadow-sm">
                <div class="p-2 rounded-xl bg-emerald-500 text-white shrink-0 mt-0.5">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">¡Tu pago ha sido acreditado exitosamente!</h3>
                    <p class="text-xs text-emerald-700 dark:text-emerald-500/90 mt-0.5">Muchas gracias por comprar en MotoSpeed. Ya estamos preparando tu pedido #{{ request()->get('order_id') }}. Te llegará un correo de confirmación en breve.</p>
                </div>
            </div>
        @elseif(request()->get('payment_status') === 'pending')
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/30 text-amber-800 dark:text-amber-400 flex items-start gap-3.5 shadow-sm">
                <div class="p-2 rounded-xl bg-amber-500 text-white shrink-0 mt-0.5">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Tu pago se encuentra pendiente</h3>
                    <p class="text-xs text-amber-700 dark:text-amber-500/90 mt-0.5">Estamos esperando la confirmación de la pasarela de pagos. Tu pedido #{{ request()->get('order_id') }} se procesará tan pronto como sea aprobado.</p>
                </div>
            </div>
        @endif

        <!-- Quick Stats Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="p-5 rounded-2xl border border-gray-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-xs">
                <div class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Compras Realizadas</div>
                <div class="text-3xl font-black text-gray-850 dark:text-white mt-1.5">{{ $orders->count() }}</div>
            </div>
            <div class="p-5 rounded-2xl border border-gray-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-xs">
                <div class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Pedidos en Preparación</div>
                <div class="text-3xl font-black text-gray-850 dark:text-white mt-1.5">{{ $orders->where('shipping_status', 'procesando')->count() }}</div>
            </div>
            <div class="p-5 rounded-2xl border border-gray-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-xs">
                <div class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Entregados con Éxito</div>
                <div class="text-3xl font-black text-gray-850 dark:text-white mt-1.5">{{ $orders->where('shipping_status', 'entregado')->count() }}</div>
            </div>
        </div>

        <!-- Orders list -->
        @if($orders->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-4 rounded-3xl border border-dashed border-gray-200 dark:border-neutral-850 text-center">
                <div class="p-4 bg-gray-50 dark:bg-neutral-900 text-gray-400 rounded-2xl mb-4">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Aún no tienes compras</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-400 max-w-sm mt-1">Cuando adquieras repuestos o accesorios en nuestra tienda, tus órdenes de compra aparecerán aquí.</p>
                <a href="{{ route('shop') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                    Ir a la tienda
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    @php
                        // Payment status pills mapping
                        $payPills = [
                            'pending' => ['label' => 'Pendiente de pago', 'css' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30'],
                            'paid' => ['label' => 'Pago aprobado', 'css' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/30'],
                            'failed' => ['label' => 'Pago fallido', 'css' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-900/30'],
                        ];
                        $payPill = $payPills[$order->status] ?? ['label' => $order->status, 'css' => 'bg-gray-100 text-gray-800 dark:bg-neutral-850 dark:text-neutral-400 border border-gray-200'];

                        // Shipping status pills mapping
                        $shipPills = [
                            'pendiente' => ['label' => 'Pendiente', 'css' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30'],
                            'con_direccion' => ['label' => 'Dirección Registrada', 'css' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-900/30'],
                            'procesando' => ['label' => 'Preparando Despacho', 'css' => 'bg-violet-100 text-violet-800 dark:bg-violet-950/40 dark:text-violet-400 border border-violet-200 dark:border-violet-900/30'],
                            'enviado' => ['label' => 'En camino', 'css' => 'bg-sky-100 text-sky-800 dark:bg-sky-950/40 dark:text-sky-400 border border-sky-200 dark:border-sky-900/30'],
                            'entregado' => ['label' => 'Entregado', 'css' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/30'],
                        ];
                        $shipPill = $shipPills[$order->shipping_status ?? 'pendiente'] ?? ['label' => $order->shipping_status, 'css' => 'bg-gray-100 text-gray-800 dark:bg-neutral-850 dark:text-neutral-400 border border-gray-200'];
                    @endphp

                    <div class="rounded-3xl border border-gray-150 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden shadow-xs">
                        
                        <!-- Card Header -->
                        <div class="p-5 border-b border-gray-150 dark:border-neutral-800 bg-gray-50/50 dark:bg-neutral-950/20 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="text-xs text-gray-400 dark:text-neutral-500 uppercase tracking-wider font-bold">Número de Pedido</div>
                                    <div class="text-lg font-black text-gray-800 dark:text-white">#{{ $order->id }}</div>
                                </div>
                                <div class="h-8 w-px bg-gray-200 dark:bg-neutral-850"></div>
                                <div>
                                    <div class="text-xs text-gray-400 dark:text-neutral-500 uppercase tracking-wider font-bold">Fecha de Compra</div>
                                    <div class="text-sm font-semibold text-gray-700 dark:text-neutral-300">{{ $order->created_at->format('d M, Y - H:i') }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $payPill['css'] }}">{{ $payPill['label'] }}</span>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $shipPill['css'] }}">{{ $shipPill['label'] }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                
                                <!-- Column 1: Items purchased -->
                                <div class="lg:col-span-2 space-y-4">
                                    <div class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Productos</div>
                                    <div class="divide-y divide-gray-100 dark:divide-neutral-850">
                                        @foreach($order->items as $item)
                                            <div class="py-3 flex justify-between items-center gap-4">
                                                <div class="flex items-center gap-3">
                                                    @if($item->product && !empty($item->product->image_url))
                                                        <img src="{{ $item->product->image_url[0] }}" class="size-11 rounded-xl object-cover border border-gray-100 dark:border-neutral-800 shrink-0">
                                                    @else
                                                        <div class="size-11 rounded-xl bg-gray-100 dark:bg-neutral-850 shrink-0"></div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-850 dark:text-white">{{ $item->product ? $item->product->name : 'Repuesto/Servicio' }}</div>
                                                        <div class="text-xs text-gray-400 dark:text-neutral-500">Cantidad: {{ $item->quantity }}</div>
                                                    </div>
                                                </div>
                                                <div class="text-sm font-bold text-gray-850 dark:text-white">
                                                    ${{ number_format($item->price * $item->quantity, 0, ',', '.') }} CLP
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Column 2: Shipping details & Tracking -->
                                <div class="lg:border-l lg:border-gray-150 lg:dark:border-neutral-800 lg:pl-6 space-y-4">
                                    <div>
                                        <div class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-2">Despacho</div>
                                        @if(is_array($order->shipping_address) && !empty($order->shipping_address['street']))
                                            <div class="text-sm text-gray-700 dark:text-neutral-300">
                                                <p class="font-bold">{{ $order->customer_name }}</p>
                                                <p class="text-xs mt-0.5">{{ $order->shipping_address['street'] }}</p>
                                                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Comuna: {{ $order->shipping_address['commune'] ?? '' }} - {{ $order->shipping_address['city'] ?? '' }}</p>
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-400 italic">No se registró dirección de despacho.</p>
                                        @endif
                                    </div>

                                    @if($order->shipping_status === 'enviado' && $order->shipping_tracking_number)
                                        <div class="pt-2">
                                            <div class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1">Código de Seguimiento</div>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 dark:bg-orange-950/20 text-orange-600 dark:text-orange-400 font-mono text-xs font-bold border border-orange-200 dark:border-orange-900/30">
                                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                {{ $order->shipping_tracking_number }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="pt-4 border-t border-gray-100 dark:border-neutral-850 flex justify-between items-center">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-neutral-400">Total pagado</span>
                                        <span class="text-lg font-black text-orange-600">${{ number_format($order->total, 0, ',', '.') }} CLP</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-layouts::client>
