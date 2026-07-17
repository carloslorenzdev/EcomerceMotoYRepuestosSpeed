@section('title', 'Pedidos y Envíos')

<div class="space-y-6" x-data="{ openModal: @entangle('isDetailModalOpen') }">
    
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300' }}">
            <span class="font-bold mr-2">{{ session('toast')['type'] === 'success' ? 'Éxito:' : 'Error:' }}</span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <!-- Toolbar: Search, Filters -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Buscar por cliente, correo o ID..."
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <svg class="absolute left-3 top-2.5 size-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Payment Status Filter -->
                <select wire:model.live="paymentStatusFilter"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos los Estados de Pago</option>
                    <option value="paid">Aprobado</option>
                    <option value="pending">Pendiente</option>
                    <option value="failed">Fallido</option>
                </select>

                <!-- Shipping Status Filter -->
                <select wire:model.live="shippingStatusFilter"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos los Estados de Despacho</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="enviado">Enviado</option>
                    <option value="entregado">Entregado</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">ID Pedido</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Pago</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Despacho</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($orders as $order)
                        <tr class="align-middle">
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
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->shipping_status === 'entregado' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : ($order->shipping_status === 'enviado' ? 'bg-sky-100 text-sky-800 dark:bg-sky-950/40 dark:text-sky-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950/40 dark:text-yellow-400') }}">
                                    {{ ucfirst($order->shipping_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="viewOrderDetails({{ $order->id }})"
                                        title="Ver detalles"
                                        class="p-1.5 rounded-lg border border-gray-200 bg-white text-gray-700 hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:bg-orange-950/20 dark:hover:text-orange-500 transition-colors">
                                    <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button type="button" wire:click="deleteOrder({{ $order->id }})"
                                        wire:confirm="¿Estás seguro de eliminar el pedido #{{ $order->id }}? Esta acción borrará el registro para siempre."
                                        title="Eliminar pedido"
                                        class="p-1.5 ml-1 rounded-lg border border-gray-200 bg-white text-gray-700 hover:border-red-200 hover:bg-red-50 hover:text-red-600 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:bg-red-950/20 dark:hover:text-red-500 transition-colors">
                                    <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                No se encontraron pedidos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal Backdrop overlay -->
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-neutral-950/60 backdrop-blur-xs" @click="openModal = false"></div>

            <!-- Modal center helper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal box -->
            @if ($selectedOrder)
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative z-10 inline-block align-bottom bg-white dark:bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200 dark:border-neutral-800">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-5 border-b border-gray-150 dark:border-neutral-800 flex justify-between items-center bg-gray-50 dark:bg-neutral-900/50">
                        <div>
                            <h3 class="text-base font-black text-gray-900 dark:text-white">Pedido #{{ $selectedOrder->id }}</h3>
                            <p class="text-[10px] text-gray-400 font-semibold mt-0.5">Fecha: {{ $selectedOrder->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-6 max-h-[70dvh] overflow-y-auto">
                        
                        <!-- Customer & Shipping info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Customer Details -->
                            <div class="rounded-xl border border-gray-100 dark:border-neutral-800 p-4">
                                <h4 class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-2.5">Datos del Cliente</h4>
                                <div class="space-y-1.5 text-xs text-gray-700 dark:text-neutral-300">
                                    <p><span class="font-bold text-gray-500">Nombre:</span> {{ $selectedOrder->customer_name }}</p>
                                    <p><span class="font-bold text-gray-500">Correo:</span> {{ $selectedOrder->customer_email }}</p>
                                    <p><span class="font-bold text-gray-500">Teléfono:</span> {{ $selectedOrder->customer_phone ?: 'No registrado' }}</p>
                                    <p><span class="font-bold text-gray-500">Gateway:</span> {{ strtoupper(str_replace('_', ' ', $selectedOrder->payment_gateway)) }}</p>
                                    <p><span class="font-bold text-gray-500">ID Pago:</span> {{ $selectedOrder->payment_id ?: 'Pendiente' }}</p>
                                </div>
                            </div>

                            <!-- Shipping Details -->
                            <div class="rounded-xl border border-gray-100 dark:border-neutral-800 p-4">
                                <h4 class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-2.5">Dirección de Despacho</h4>
                                <div class="text-xs text-gray-700 dark:text-neutral-300 space-y-1">
                                    @if (is_array($selectedOrder->shipping_address))
                                        <p class="font-semibold">{{ $selectedOrder->shipping_address['street'] ?? '' }}</p>
                                        <p>{{ $selectedOrder->shipping_address['commune'] ?? '' }}, {{ $selectedOrder->shipping_address['city'] ?? '' }}</p>
                                        <p class="text-gray-400">Región: {{ $selectedOrder->shipping_address['region'] ?? 'Rancagua' }}</p>
                                        @if (!empty($selectedOrder->shipping_address['notes']))
                                            <p class="mt-2 text-orange-500 bg-orange-50 dark:bg-orange-950/20 p-2 rounded-sm text-[10px]">
                                                <span class="font-bold block uppercase tracking-wider mb-0.5">Indicaciones:</span>
                                                {{ $selectedOrder->shipping_address['notes'] }}
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-gray-400">Sin datos de dirección o retiro en tienda.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Items List -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-3">Artículos del Pedido</h4>
                            <div class="border border-gray-100 dark:border-neutral-800 rounded-xl overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-100 dark:divide-neutral-800 text-left text-xs">
                                    <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500">
                                        <tr>
                                            <th class="px-4 py-2.5 font-bold">Producto</th>
                                            <th class="px-4 py-2.5 font-bold text-center">Cant.</th>
                                            <th class="px-4 py-2.5 font-bold text-right">Unitario</th>
                                            <th class="px-4 py-2.5 font-bold text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                                        @foreach($selectedOrder->items as $item)
                                            <tr>
                                                <td class="px-4 py-3 font-semibold text-gray-800 dark:text-neutral-200">
                                                    {{ $item->product ? $item->product->name : 'Producto Eliminado' }}
                                                </td>
                                                <td class="px-4 py-3 text-center text-gray-800 dark:text-neutral-350">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="px-4 py-3 text-right text-gray-800 dark:text-neutral-350">
                                                    ${{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">
                                                    ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- Order Total Row -->
                                        <tr class="bg-gray-50 dark:bg-neutral-850/50">
                                            <td colspan="3" class="px-4 py-3 font-bold text-right text-gray-600 dark:text-neutral-400">Total Pedido:</td>
                                            <td class="px-4 py-3 font-black text-right text-base text-gray-900 dark:text-white">
                                                ${{ number_format($selectedOrder->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Update Shipping Buttons -->
                        <div class="border-t border-gray-100 dark:border-neutral-800 pt-6 space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider">Acciones rápidas de Despacho</h4>
                            
                            <div class="flex flex-wrap items-center gap-3">
                                @php
                                    $status = $selectedOrder->shipping_status ?? 'pendiente';
                                    $isPaid = $selectedOrder->status === 'paid';
                                    $hasAddress = is_array($selectedOrder->shipping_address) && !empty($selectedOrder->shipping_address['street']);
                                @endphp

                                @if($isPaid)
                                    @if($status === 'pendiente' || $status === 'con_direccion')
                                        <button type="button" wire:click="updateShippingStatus({{ $selectedOrder->id }}, 'procesando')"
                                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            Preparar pedido
                                        </button>
                                    @endif

                                    @if($status === 'procesando' || $status === 'con_direccion' || $status === 'pendiente')
                                        <button type="button" wire:click="markAsShipped({{ $selectedOrder->id }})"
                                                @if(!$hasAddress) disabled @endif
                                                title="{{ !$hasAddress ? 'El cliente aún no ha registrado su dirección de envío.' : '' }}"
                                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Marcar Enviado (Tracking Auto)
                                        </button>
                                    @endif

                                    @if($status === 'enviado')
                                        <button type="button" wire:click="updateShippingStatus({{ $selectedOrder->id }}, 'entregado')"
                                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Confirmar entrega
                                        </button>
                                    @endif

                                    @if($status === 'entregado')
                                        <span class="inline-flex items-center gap-2 px-4 py-2.5 border border-emerald-200 bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 text-xs font-bold rounded-xl">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Pedido entregado con éxito
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-amber-600 dark:text-amber-500 font-bold bg-amber-50 dark:bg-amber-950/20 p-2.5 rounded-xl border border-amber-200 dark:border-amber-900/30">
                                        El pago aún no ha sido aprobado por Mercado Pago. No se pueden procesar despachos.
                                    </span>
                                @endif
                            </div>

                            <!-- Modal Actions Footer -->
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-150 dark:border-neutral-800">
                                <button type="button" @click="openModal = false" class="px-5 py-2.5 text-xs font-bold rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-300 transition-colors">
                                    Cerrar Detalles
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
