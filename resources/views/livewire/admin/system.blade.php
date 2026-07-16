@section('title', 'Sistema y Logs')

<div class="space-y-6" x-data="{ openPayloadModal: @entangle('isPayloadModalOpen') }">
    
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300' }}">
            <span class="font-bold mr-2">{{ session('toast')['type'] === 'success' ? 'Éxito:' : 'Error:' }}</span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <div class="w-full">
        
        <!-- Webhook Logs History -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Historial de Webhooks</h2>
                    <p class="text-[10px] text-gray-400 mt-0.5">Logs de peticiones entrantes de Mercado Pago y RelBase.</p>
                </div>
                
                <button type="button" wire:click="clearAllLogs" onclick="confirm('¿Estás seguro de que deseas vaciar los logs?') || event.stopImmediatePropagation()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 dark:border-red-900/50 dark:bg-neutral-800 dark:text-red-400 dark:hover:bg-red-950/20 transition-colors">
                    Limpiar Logs
                </button>
            </div>

            <!-- Webhook Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <select wire:model.live="providerFilter"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos los Proveedores</option>
                    <option value="relbase">RelBase</option>
                    <option value="mercado_pago">Mercado Pago</option>
                </select>

                <select wire:model.live="statusFilter"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos los Estados</option>
                    <option value="processed">Procesado</option>
                    <option value="failed">Fallo</option>
                    <option value="skipped">Omitido</option>
                </select>
            </div>

            <!-- Logs Table -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                        <tr>
                            <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Fecha</th>
                            <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Proveedor</th>
                            <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Estado</th>
                            <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Detalles / Error</th>
                            <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Payload</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                        @forelse ($logs as $log)
                            <tr class="align-middle">
                                <td class="px-4 py-4 text-gray-500">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                
                                <td class="px-4 py-4 font-bold uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $log->provider) }}
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider
                                        {{ $log->status === 'processed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : ($log->status === 'skipped' ? 'bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-neutral-400' : 'bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400') }}">
                                        {{ $log->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 max-w-xs truncate text-[10px] text-gray-600 dark:text-neutral-400" title="{{ $log->error }}">
                                    {{ $log->error ?: 'Completado sin observaciones.' }}
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <button type="button" wire:click="viewPayload({{ $log->id }})"
                                            class="p-1 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-750 transition-colors">
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    No se encontraron logs de webhooks registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>

    </div>

    <!-- Payload detail Modal -->
    <div x-show="openPayloadModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal Backdrop overlay -->
            <div x-show="openPayloadModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-neutral-950/60 backdrop-blur-xs" @click="openPayloadModal = false"></div>

            <!-- Modal center helper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal box -->
            @if ($selectedLog)
                <div x-show="openPayloadModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative z-10 inline-block align-bottom bg-white dark:bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-200 dark:border-neutral-800">
                    
                    <div class="px-6 py-5 border-b border-gray-150 dark:border-neutral-800 flex justify-between items-center bg-gray-50 dark:bg-neutral-900/50">
                        <h3 class="text-base font-black text-gray-900 dark:text-white">Payload Webhook #{{ $selectedLog->id }}</h3>
                        <button type="button" @click="openPayloadModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <pre class="bg-neutral-950 text-emerald-450 p-4 rounded-xl overflow-x-auto text-[10px] font-mono leading-relaxed max-h-[50dvh] overflow-y-auto shadow-inner">{{ json_encode($selectedLog->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                        
                        <div class="flex justify-end pt-4 mt-4 border-t border-gray-150 dark:border-neutral-800">
                            <button type="button" @click="openPayloadModal = false" class="px-5 py-2 text-xs font-bold rounded-xl bg-orange-600 hover:bg-orange-700 text-white transition-colors">
                                Entendido
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
