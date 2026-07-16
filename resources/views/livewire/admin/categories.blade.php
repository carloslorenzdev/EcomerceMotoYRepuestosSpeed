@section('title', 'Categorías de Productos')

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

    <!-- Toolbar: Search, Create -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Search Input -->
            <div class="relative w-full sm:max-w-xs">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar categoría..."
                       class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                <svg class="absolute left-3 top-2.5 size-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Create trigger button -->
            <button type="button" wire:click="createCategory"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-bold text-white shadow-xs hover:bg-orange-700 transition-all">
                <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Categoría
            </button>

        </div>
    </div>

    <!-- Categories List Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Nombre</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Descripción</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Productos Relacionados</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Visibilidad</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($categories as $cat)
                        <tr class="align-middle">
                            <!-- Category Name / Slug -->
                            <td class="px-4 py-4">
                                <span class="font-bold text-gray-900 dark:text-white block text-sm">{{ $cat->name }}</span>
                                <span class="text-[10px] text-gray-400 font-medium block mt-0.5">{{ $cat->slug }}</span>
                            </td>

                            <!-- Description -->
                            <td class="px-4 py-4 text-gray-600 dark:text-neutral-400 max-w-sm truncate">
                                {{ $cat->description ?: 'Sin descripción registrada.' }}
                            </td>

                            <!-- Product count -->
                            <td class="px-4 py-4 text-center font-bold text-gray-800 dark:text-neutral-350">
                                {{ $cat->products_count }}
                            </td>

                            <!-- Visibility toggle -->
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="toggleActive({{ $cat->id }})" 
                                        class="inline-flex items-center size-5 rounded-md border transition-all justify-center
                                        {{ $cat->is_active ? 'bg-emerald-500 border-emerald-600 text-white' : 'bg-gray-100 border-gray-300 dark:bg-neutral-800 dark:border-neutral-700 text-transparent' }}">
                                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </td>

                            <!-- Edit action -->
                            <td class="px-4 py-4 text-center">
                                <button type="button" wire:click="editCategory({{ $cat->id }})"
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
                                No se encontraron categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Category Modal -->
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
                        {{ $editingCategoryId ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h3>
                    <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveCategory" class="p-6 space-y-4">
                    
                    <!-- Row 1: Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre de la Categoría</label>
                        <input type="text" wire:model="name" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                        @error('name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Row 2: Description -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Descripción</label>
                        <textarea rows="3" wire:model="description" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Row 3: Visibility flag -->
                    <div class="pt-2">
                        <label class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-neutral-350 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded-sm border-gray-300 dark:border-neutral-750 dark:bg-neutral-800 text-orange-600 focus:ring-orange-500 size-4">
                            Habilitar Visibilidad
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
