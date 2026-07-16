@section('title', 'Reportes de Rendimiento')

<div class="space-y-6">
    
    <!-- Annual Monthly Sales Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div>
            <h2 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Ventas Mensuales (Año Actual)</h2>
            <p class="text-[10px] text-gray-400 mt-0.5">Volumen total de facturación mensual por pedidos completados.</p>
        </div>

        <div class="mt-8 flex justify-center w-full">
            <svg viewBox="0 0 650 200" class="w-full max-w-2xl h-auto" xmlns="http://www.w3.org/2000/svg">
                <!-- Grid Lines -->
                <line x1="10" y1="170" x2="630" y2="170" stroke="currentColor" class="text-gray-200 dark:text-neutral-850" stroke-width="1.5" />
                <line x1="10" y1="95" x2="630" y2="95" stroke="currentColor" class="text-gray-100 dark:text-neutral-880" stroke-width="1" stroke-dasharray="4" />
                <line x1="10" y1="20" x2="630" y2="20" stroke="currentColor" class="text-gray-100 dark:text-neutral-880" stroke-width="1" stroke-dasharray="4" />

                @foreach ($monthlySales as $idx => $day)
                    @php
                        $barHeight = $maxMonthlySales > 0 ? ($day['total'] / $maxMonthlySales) * 140 : 0;
                        $barHeight = max(2, $barHeight);
                        $xPos = $idx * 50 + 25;
                        $yPos = 170 - $barHeight;
                    @endphp

                    <!-- Bar -->
                    <rect x="{{ $xPos }}" y="{{ $yPos }}" width="24" height="{{ $barHeight }}" rx="3" ry="3"
                          class="fill-orange-500 hover:fill-orange-600 dark:fill-orange-600 dark:hover:fill-orange-500 transition-all duration-200 cursor-pointer" />

                    <!-- Axis Label -->
                    <text x="{{ $xPos + 12 }}" y="186" text-anchor="middle" font-size="10" class="fill-gray-400 dark:fill-neutral-500 font-bold">
                        {{ $day['month'] }}
                    </text>

                    <!-- Tooltip value -->
                    @if ($day['total'] > 0)
                        <text x="{{ $xPos + 12 }}" y="{{ $yPos - 6 }}" text-anchor="middle" font-size="8" class="fill-gray-500 dark:fill-neutral-400 font-bold">
                            ${{ number_format($day['total'] / 1000, 0, ',', '') }}k
                        </text>
                    @endif
                @endforeach
            </svg>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Category Sales Share -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm flex flex-col justify-between">
            <div class="mb-4">
                <h2 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Distribución por Categorías</h2>
                <p class="text-[10px] text-gray-400 mt-0.5">Ingresos acumulados agrupados por categoría de producto.</p>
            </div>

            <div class="space-y-4 flex-1">
                @forelse($categorySales as $cat)
                    @php
                        $maxCatSales = collect($categorySales)->max('total_sales') ?: 1;
                        $percentage = ($cat->total_sales / $maxCatSales) * 100;
                    @endphp
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-semibold text-gray-700 dark:text-neutral-300">
                            <span>{{ $cat->category_name }}</span>
                            <span>${{ number_format($cat->total_sales, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-neutral-800 rounded-full h-2 overflow-hidden">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-xs">
                        No hay datos de ventas disponibles.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Selling Products List -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Productos Más Vendidos</h2>
                <p class="text-[10px] text-gray-400 mt-0.5">Top 5 de artículos con mayor cantidad de unidades despachadas.</p>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-150 dark:border-neutral-800">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-neutral-800 text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500">
                        <tr>
                            <th class="px-4 py-2.5 font-bold">Producto</th>
                            <th class="px-4 py-2.5 font-bold text-center">Unidades</th>
                            <th class="px-4 py-2.5 font-bold text-right">Valorizado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                        @forelse($topProducts as $prod)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-800 dark:text-neutral-200">
                                    {{ $prod->name }}
                                    <span class="block text-[10px] text-gray-450 uppercase font-bold mt-0.5">SKU: {{ $prod->sku }}</span>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-gray-900 dark:text-white">
                                    {{ $prod->total_qty }} un.
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                    ${{ number_format($prod->total_val, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-450">
                                    No hay registros de ventas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
