@section('title', 'Catálogo de Productos')

<div class="space-y-6" x-data="{ openModal: @entangle('isEditModalOpen') }">
    
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300' }}">
            <span class="font-bold mr-2">{{ session('toast')['type'] === 'success' ? 'Éxito:' : 'Error:' }}</span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <!-- Toolbar: Search, Filters, Sync -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Search & Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full md:max-w-3xl">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Buscar por nombre o SKU..."
                           class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <svg class="absolute left-3 top-2.5 size-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Category Filter -->
                <select wire:model.live="categoryFilter"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <option value="">Todas las Categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- Stock Filter -->
                <select wire:model.live="stockFilter"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos los Inventarios</option>
                    <option value="in">Con Stock (> 5)</option>
                    <option value="low">Bajo Stock (1 - 5)</option>
                    <option value="out">Sin Stock (0)</option>
                </select>
            </div>

            <!-- Sync trigger button -->
            <button type="button" wire:click="syncFromRelBase" wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-orange-700 disabled:opacity-50 transition-all">
                <span wire:loading.remove wire:target="syncFromRelBase" class="flex items-center gap-2">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2"/>
                    </svg>
                    Sincronizar RelBase
                </span>
                <span wire:loading wire:target="syncFromRelBase" class="flex items-center gap-2">
                    <svg class="animate-spin size-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sincronizando...
                </span>
            </button>

        </div>
    </div>

    <!-- Catalog Table Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Producto</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Categoría</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Precio</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Stock</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Visibilidad</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Destacado</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($products as $prod)
                        <tr class="align-middle">
                            <!-- Product Info -->
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-gray-100 dark:bg-neutral-800 flex items-center justify-center">
                                        @if(!empty($prod->image_url) && isset($prod->image_url[0]))
                                            <img src="{{ $prod->image_url[0] }}" alt="{{ $prod->name }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="size-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white truncate max-w-xs">{{ $prod->name }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold mt-0.5">SKU: {{ $prod->sku }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-4 text-gray-700 dark:text-neutral-350">
                                {{ $prod->category->name }}
                            </td>

                            <!-- Price -->
                            <td class="px-4 py-4">
                                <div class="flex flex-col">
                                    @if ($prod->compare_at_price)
                                        <span class="text-[10px] text-gray-400 line-through">${{ number_format($prod->compare_at_price, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="font-bold text-gray-900 dark:text-white">${{ number_format($prod->price, 0, ',', '.') }}</span>
                                </div>
                            </td>

                            <!-- Stock -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                    {{ $prod->stock > 5 ? 'bg-sky-100 text-sky-800 dark:bg-sky-950/40 dark:text-sky-400' : ($prod->stock > 0 ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400') }}">
                                    {{ $prod->stock }} un.
                                </span>
                            </td>

                            <!-- Visibility Switch -->
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="toggleActive({{ $prod->id }})" 
                                        class="inline-flex items-center size-5 rounded-md border transition-all justify-center
                                        {{ $prod->is_active ? 'bg-emerald-500 border-emerald-600 text-white' : 'bg-gray-100 border-gray-300 dark:bg-neutral-800 dark:border-neutral-700 text-transparent' }}">
                                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </td>

                            <!-- Featured Switch -->
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="toggleFeatured({{ $prod->id }})" 
                                        class="inline-flex items-center size-5 rounded-md border transition-all justify-center
                                        {{ $prod->is_featured ? 'bg-amber-500 border-amber-600 text-white' : 'bg-gray-100 border-gray-300 dark:bg-neutral-800 dark:border-neutral-700 text-transparent' }}">
                                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="editProduct({{ $prod->id }})"
                                        class="p-1.5 rounded-lg border border-gray-200 bg-white text-gray-700 hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:bg-orange-950/20 dark:hover:text-orange-500 transition-colors">
                                    <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
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

    <!-- Product Edit Modal -->
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal Backdrop overlay -->
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-neutral-950/60 backdrop-blur-xs" @click="openModal = false"></div>

            <!-- Modal center helper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal box -->
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative z-10 inline-block align-bottom bg-white dark:bg-neutral-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-200 dark:border-neutral-800">
                
                <div class="px-6 py-5 border-b border-gray-150 dark:border-neutral-800 flex justify-between items-center bg-gray-50 dark:bg-neutral-900/50">
                    <h3 class="text-base font-black text-gray-900 dark:text-white">Editar Información de Producto</h3>
                    <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveProduct" class="p-6 space-y-4">
                    
                    <!-- Row 1: Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre del Producto</label>
                        <input type="text" wire:model="name" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                        @error('name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Row 2: SKU & Category -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">SKU / Código</label>
                            <input type="text" wire:model="sku" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                            @error('sku') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Categoría</label>
                            <select wire:model="category_id" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                                <option value="">Seleccione...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Row 3: Price & Offer Price -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Precio ($)</label>
                            <input type="number" wire:model="price" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                            @error('price') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Precio Oferta ($)</label>
                            <input type="number" wire:model="compare_at_price" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                            @error('compare_at_price') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Stock</label>
                            <input type="number" wire:model="stock" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                            @error('stock') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Row 4: Description -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Descripción</label>
                        <textarea rows="3" wire:model="description" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Row 4.5: Product Images -->
                    <div class="border-t border-gray-150 dark:border-neutral-800 pt-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Imágenes del Producto</label>
                        
                        <!-- List of current images -->
                        @if(!empty($image_urls))
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                @foreach($image_urls as $index => $url)
                                    <div class="relative group h-16 rounded-xl overflow-hidden border border-gray-150 dark:border-neutral-800 bg-gray-50 dark:bg-neutral-900">
                                        <img src="{{ $url }}" class="h-full w-full object-cover">
                                        <button type="button" wire:click="removeImageUrl({{ $index }})" class="absolute top-1 right-1 p-1 rounded-full bg-red-600 text-white opacity-90 hover:opacity-100 hover:scale-105 active:scale-95 transition-all shadow-xs">
                                            <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 mb-3">Este producto no tiene imágenes registradas.</p>
                        @endif

                        <!-- Add new image URL -->
                        <div class="flex gap-2">
                            <input type="text" wire:model="newImageUrl" placeholder="https://ejemplo.com/imagen.jpg" class="flex-1 px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                            <button type="button" wire:click="addImageUrl" class="px-4 py-2.5 text-xs font-bold bg-neutral-900 dark:bg-neutral-850 hover:bg-orange-600 text-white rounded-xl transition">
                                Añadir
                            </button>
                        </div>
                        @error('newImageUrl') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Row 5: Featured & Visibility flags -->
                    <div class="flex items-center gap-6 pt-2">
                        <label class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-neutral-350 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded-sm border-gray-300 dark:border-neutral-750 dark:bg-neutral-800 text-orange-600 focus:ring-orange-500 size-4">
                            Habilitar Visibilidad
                        </label>

                        <label class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-neutral-350 cursor-pointer">
                            <input type="checkbox" wire:model="is_featured" class="rounded-sm border-gray-300 dark:border-neutral-750 dark:bg-neutral-800 text-orange-600 focus:ring-orange-500 size-4">
                            Destacar Producto
                        </label>
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-150 dark:border-neutral-800">
                        <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-neutral-750 dark:bg-neutral-800 dark:text-neutral-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 text-sm font-bold rounded-xl bg-orange-600 hover:bg-orange-700 text-white transition-colors">
                            Guardar Cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
