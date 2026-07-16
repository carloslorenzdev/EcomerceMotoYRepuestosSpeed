@section('title', 'Control de Inventario')

<div class="space-y-6">
    
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : (session('toast')['type'] === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950 dark:border-amber-900 dark:text-amber-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300') }}">
            <span class="font-bold mr-2">
                {{ session('toast')['type'] === 'success' ? 'Éxito:' : (session('toast')['type'] === 'warning' ? 'Advertencia:' : 'Error:') }}
            </span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <!-- Toolbar: Search & Filters -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Search & Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full sm:max-w-xl">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Buscar por nombre o SKU..."
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <svg class="absolute left-3 top-2.5 size-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Stock Filter -->
                <select wire:model.live="stockFilter"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <option value="all">Todos los Niveles de Stock</option>
                    <option value="low">Bajo Stock (1 - 5)</option>
                    <option value="out">Sin Stock (0)</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Inventory Table Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">SKU</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Producto</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Categoría</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Nivel de Stock</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Modificar Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($products as $prod)
                        <tr class="align-middle">
                            <!-- SKU -->
                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $prod->sku }}
                            </td>

                            <!-- Product Name -->
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-800 dark:text-neutral-200 truncate max-w-sm" title="{{ $prod->name }}">
                                    {{ $prod->name }}
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-4 text-gray-600 dark:text-neutral-400">
                                {{ $prod->category->name }}
                            </td>

                            <!-- Stock Status Badge -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                    {{ $prod->stock > 5 ? 'bg-sky-100 text-sky-800 dark:bg-sky-950/40 dark:text-sky-400' : ($prod->stock > 0 ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400') }}">
                                    {{ $prod->stock }} unidades
                                </span>
                            </td>

                            <!-- Inline stock modifier input -->
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="number" wire:model.defer="editingStocks.{{ $prod->id }}"
                                           class="w-20 px-2 py-1.5 text-center text-xs rounded-lg border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-1 focus:ring-orange-500 focus:outline-hidden"
                                           min="0">
                                    
                                    <!-- Save Button -->
                                    <button type="button" wire:click="updateStock({{ $prod->id }}, editingStocks[{{ $prod->id }}])"
                                            class="p-1.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold transition-all shadow-xs"
                                            title="Guardar Stock">
                                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                No se encontraron productos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

</div>
