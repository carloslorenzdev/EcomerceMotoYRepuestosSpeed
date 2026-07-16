@section('title', 'Almacenes y Bodegas')

<div class="space-y-6" x-data="{ openModal: @entangle('isModalOpen') }">
    
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300' }}">
            <span class="font-bold mr-2">{{ session('toast')['type'] === 'success' ? 'Éxito:' : 'Error:' }}</span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <!-- Toolbar -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="relative w-full sm:max-w-xs">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar almacén..."
                       class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                <svg class="absolute left-3 top-2.5 size-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <button type="button" wire:click="createWarehouse"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-orange-700 transition-all">
                <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Almacén
            </button>

        </div>
    </div>

    <!-- List -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Nombre</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Código de Almacén</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Dirección / Ubicación</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Estado</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($warehouses as $wh)
                        <tr class="align-middle">
                            <td class="px-4 py-4 font-bold text-gray-900 dark:text-white text-sm">
                                {{ $wh->name }}
                            </td>
                            <td class="px-4 py-4 font-semibold text-orange-600 dark:text-orange-500 uppercase">
                                {{ $wh->code }}
                            </td>
                            <td class="px-4 py-4 text-gray-600 dark:text-neutral-400">
                                {{ $wh->address ?: 'Sin dirección registrada.' }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="toggleActive({{ $wh->id }})" 
                                        class="inline-flex items-center size-5 rounded-md border transition-all justify-center
                                        {{ $wh->is_active ? 'bg-emerald-500 border-emerald-600 text-white' : 'bg-gray-100 border-gray-300 dark:bg-neutral-800 dark:border-neutral-700 text-transparent' }}">
                                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="editWarehouse({{ $wh->id }})"
                                        class="p-1.5 rounded-lg border border-gray-200 bg-white text-gray-700 hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:bg-orange-950/20 dark:hover:text-orange-500 transition-colors">
                                    <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                No se encontraron almacenes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal Backdrop overlay -->
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-neutral-950/60 backdrop-blur-xs" @click="openModal = false"></div>

            <!-- Modal center helper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal box -->
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative z-10 inline-block align-bottom bg-white dark:bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-200 dark:border-neutral-800">
                
                <div class="px-6 py-5 border-b border-gray-150 dark:border-neutral-800 flex justify-between items-center bg-gray-50 dark:bg-neutral-900/50">
                    <h3 class="text-base font-black text-gray-900 dark:text-white">
                        {{ $editingWarehouseId ? 'Editar Almacén' : 'Nuevo Almacén' }}
                    </h3>
                    <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveWarehouse" class="p-6 space-y-4">
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre del Almacén</label>
                        <input type="text" wire:model="name" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                        @error('name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Código Único (Ej: 'principal', 'bodega2')</label>
                        <input type="text" wire:model="code" {{ $editingWarehouseId ? 'disabled' : '' }}
                               class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 disabled:opacity-60 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500 font-mono">
                        @error('code') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dirección / Ubicación física</label>
                        <input type="text" wire:model="address" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                        @error('address') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Visibility flag -->
                    <div class="pt-2">
                        <label class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-neutral-350 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded-sm border-gray-300 dark:border-neutral-750 dark:bg-neutral-800 text-orange-600 focus:ring-orange-500 size-4">
                            Habilitar Almacén
                        </label>
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-150 dark:border-neutral-800">
                        <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 text-sm font-bold rounded-xl bg-orange-600 hover:bg-orange-700 text-white transition-colors">
                            Guardar Almacén
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
