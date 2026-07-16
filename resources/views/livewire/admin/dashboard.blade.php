@section('title', 'Resumen General')

<div class="space-y-6">
    
    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        
        <!-- Total Ventas -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-500 to-amber-500"></div>
            <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Total Ventas</p>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                ${{ number_format($stats['total_sales'], 0, ',', '.') }}
            </p>
        </div>

        <!-- Pedidos Realizados -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Pedidos Totales</p>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ $stats['total_orders'] }}
            </p>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-500 to-orange-500"></div>
            <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Ped. Pendientes</p>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ $stats['pending_orders'] }}
            </p>
        </div>

        <!-- Productos Activos -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
            <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Prod. Visibles</p>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ $stats['active_products'] }}
            </p>
        </div>

        <!-- Bajo Stock -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
            <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Bajo Stock (≤5)</p>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white tracking-tight text-red-600 dark:text-red-500">
                {{ $stats['low_stock_products'] }}
            </p>
        </div>

        <!-- Clientes Registrados -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm relative overflow-hidden transition-all hover:shadow-md">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
            <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Usuarios Reg.</p>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ $stats['total_users'] }}
            </p>
        </div>

    </div>

    <!-- Charts & Logs Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <!-- Sales Chart Card -->
        <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Gráfico semanal</p>
                <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight mt-1">Ventas de los Últimos 7 Días</h2>
            </div>
            
            <div class="mt-6 flex justify-center w-full">
                <!-- Inline SVG Bar Chart -->
                <svg viewBox="0 0 520 200" class="w-full max-w-xl h-auto" xmlns="http://www.w3.org/2000/svg">
                    <!-- Grid Lines -->
                    <line x1="10" y1="170" x2="510" y2="170" stroke="currentColor" class="text-gray-200 dark:text-neutral-800" stroke-width="1.5" />
                    <line x1="10" y1="95" x2="510" y2="95" stroke="currentColor" class="text-gray-100 dark:text-neutral-850" stroke-width="1" stroke-dasharray="4" />
                    <line x1="10" y1="20" x2="510" y2="20" stroke="currentColor" class="text-gray-100 dark:text-neutral-850" stroke-width="1" stroke-dasharray="4" />
                    
                    @foreach ($salesData as $idx => $day)
                        @php
                            $barHeight = $maxSales > 0 ? ($day['total'] / $maxSales) * 140 : 0;
                            $barHeight = max(4, $barHeight); // minimum height to show a pixel line if total is zero or small
                            $xPos = $idx * 70 + 25;
                            $yPos = 170 - $barHeight;
                        @endphp
                        
                        <!-- Bar -->
                        <rect x="{{ $xPos }}" y="{{ $yPos }}" width="34" height="{{ $barHeight }}" rx="4" ry="4" 
                              class="fill-orange-500 dark:fill-orange-600 hover:fill-orange-600 dark:hover:fill-orange-500 transition-all duration-200 cursor-pointer" />
                        
                        <!-- Values on hover/display -->
                        <text x="{{ $xPos + 17 }}" y="{{ $yPos - 8 }}" text-anchor="middle" font-size="9" class="fill-gray-500 dark:fill-neutral-400 font-bold">
                            @if ($day['total'] > 0)
                                ${{ number_format($day['total'] / 1000, 0, ',', '') }}k
                            @endif
                        </text>
                        
                        <!-- Axis Labels -->
                        <text x="{{ $xPos + 17 }}" y="186" text-anchor="middle" font-size="10" class="fill-gray-400 dark:fill-neutral-500 font-semibold">
                            {{ $day['day'] }}
                        </text>
                    @endforeach
                </svg>
            </div>
        </div>

        <!-- Webhook logs Card -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center">
                    <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Actividades del sistema</p>
                    <span class="inline-flex size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight mt-1">Logs de Webhooks</h2>
            </div>

            <div class="mt-4 flex-1 space-y-3.5 overflow-y-auto max-h-[160px] pr-2">
                @forelse($recentLogs as $log)
                    <div class="flex items-start gap-3 text-xs border-b border-gray-100 dark:border-neutral-800 pb-3 last:border-b-0 last:pb-0">
                        <span class="inline-flex items-center justify-center size-7 rounded-lg font-bold shrink-0
                            {{ $log->status === 'processed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300' }}">
                            {{ $log->provider === 'relbase' ? 'RB' : 'MP' }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-800 dark:text-neutral-200 truncate">
                                {{ $log->provider === 'relbase' ? 'Sincronización RelBase' : 'Notificación de Pago' }}
                            </p>
                            <p class="text-neutral-500 dark:text-neutral-400 text-[10px] mt-0.5">
                                {{ $log->created_at->diffForHumans() }} • Estado: <span class="font-bold">{{ $log->status }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-6">
                        <p class="text-xs text-gray-400">No hay registros de webhooks recientes</p>
                    </div>
                @endforelse
            </div>
            
            <a href="{{ route('admin.system') }}" class="mt-4 block text-center text-xs font-semibold text-orange-600 hover:text-orange-500 dark:text-orange-500 dark:hover:text-orange-400 transition-colors">
                Ver todos los logs de sistema →
            </a>
        </div>

    </div>

    <!-- Recent Orders Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Ventas recientes</p>
                <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight mt-1">Últimos Pedidos Recibidos</h2>
            </div>
            <a href="{{ route('admin.orders') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-750 transition-colors">
                Ver todos los pedidos
            </a>
        </div>

        <div class="mt-6 overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">ID Pedido</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Pago</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Despacho</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">
                                #{{ $order->id }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-800 dark:text-neutral-200">{{ $order->customer_name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $order->customer_email }}</div>
                            </td>
                            <td class="px-4 py-4 font-bold text-gray-900 dark:text-white">
                                ${{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->status === 'paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : ($order->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400') }}">
                                    {{ $order->status === 'paid' ? 'Aprobado' : ($order->status === 'pending' ? 'Pendiente' : 'Fallido') }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->shipping_status === 'entregado' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : ($order->shipping_status === 'enviado' ? 'bg-sky-100 text-sky-800 dark:bg-sky-950/40 dark:text-sky-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950/40 dark:text-yellow-400') }}">
                                    {{ ucfirst($order->shipping_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                No se encontraron pedidos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
