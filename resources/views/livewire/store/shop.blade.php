<div x-data="{ isFiltersOpen: false }">
  <!-- Header Title -->
  <div class="bg-neutral-900 text-white py-12">
    <div class="max-w-[85rem] mx-auto px-4">
      <h1 class="text-3xl font-extrabold font-title">Tienda de Repuestos y Accesorios</h1>
      <p class="text-sm text-neutral-400 mt-2">Encuentra repuestos originales Bajaj y componentes multimarcas de alta calidad.</p>
    </div>
  </div>

  <!-- Catalog Section -->
  <div class="max-w-[85rem] mx-auto px-4 py-10">
    <div class="grid lg:grid-cols-12 gap-8">
      
      <!-- Left Sidebar (Desktop Filters) -->
      <aside class="hidden lg:block lg:col-span-3 space-y-6">
        
        <!-- Category Filter -->
        <div class="bg-white border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800">
          <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Categorías</h3>
          <div class="space-y-2">
            <button type="button" 
                    wire:click="filterCategory('')" 
                    class="w-full flex items-center justify-between text-sm py-1.5 px-2 rounded-lg text-left {{ $categorySlug === '' ? 'bg-orange-50 text-orange-600 dark:bg-orange-950/20 dark:text-orange-400 font-semibold' : 'text-gray-600 hover:bg-gray-50 dark:text-neutral-400 dark:hover:bg-neutral-800' }}">
              <span>Todos los productos</span>
            </button>
            @foreach ($categories as $cat)
              <button type="button" 
                      wire:click="filterCategory('{{ $cat->slug }}')" 
                      class="w-full flex items-center justify-between text-sm py-1.5 px-2 rounded-lg text-left {{ $categorySlug === $cat->slug ? 'bg-orange-50 text-orange-600 dark:bg-orange-950/20 dark:text-orange-400 font-semibold' : 'text-gray-600 hover:bg-gray-50 dark:text-neutral-400 dark:hover:bg-neutral-800' }}">
                <span class="truncate">{{ $cat->name }}</span>
                <span class="text-xs bg-gray-100 text-gray-500 py-0.5 px-2 rounded-full dark:bg-neutral-800 dark:text-neutral-400">{{ $cat->products_count }}</span>
              </button>
            @endforeach
          </div>
        </div>

        <!-- Price Range Filter -->
        <div class="bg-white border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800">
          <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Rango de Precios</h3>
          <div class="space-y-4">
            <div class="flex gap-2">
              <input type="number" wire:model="minPrice" placeholder="Mín" class="w-full text-xs rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2 focus:border-orange-500 focus:ring-orange-500">
              <input type="number" wire:model="maxPrice" placeholder="Máx" class="w-full text-xs rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2 focus:border-orange-500 focus:ring-orange-500">
            </div>
            <button type="button" wire:click="applyPriceFilter" class="w-full p-2 text-xs font-bold bg-neutral-900 dark:bg-neutral-800 hover:bg-orange-600 text-white rounded-lg transition">
              Filtrar Rango
            </button>
          </div>
        </div>

        <!-- Reset Button -->
        <button type="button" wire:click="resetFilters" class="w-full p-3 text-xs font-bold border border-gray-200 hover:bg-gray-50 text-gray-600 dark:border-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-800 rounded-xl transition">
          Limpiar Filtros
        </button>

      </aside>

      <!-- Right Products Grid -->
      <div class="lg:col-span-9 space-y-6">
        
        <!-- Controls Bar (Search + Sort) -->
        <div class="bg-white border border-gray-150 rounded-2xl p-4 dark:bg-neutral-850 dark:border-neutral-800 flex flex-col sm:flex-row items-center justify-between gap-4">
          <!-- Search input -->
          <div class="relative w-full sm:max-w-xs">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Buscar por SKU, nombre..." 
                   class="w-full text-sm rounded-lg border-gray-200 pl-10 pr-4 py-2.5 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 focus:border-orange-500 focus:ring-orange-500">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
              <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </span>
          </div>

          <!-- Mobile Filters Collapse (Visible on mobile only) -->
          <div class="flex items-center justify-between w-full sm:w-auto gap-4">
            <button type="button" @click="isFiltersOpen = true" class="py-2.5 px-4 lg:hidden inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-850 dark:border-neutral-700 dark:text-white">
              Filtrar
              <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 8.293A1 1 0 013 7.586V4z"/>
              </svg>
            </button>

            <!-- Sorting Select -->
            <div class="flex items-center gap-2">
              <label class="hidden sm:inline text-xs font-bold text-gray-400 uppercase tracking-wider shrink-0">Ordenar:</label>
              <select wire:model.live="sortBy" class="text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 py-2.5 focus:border-orange-500 focus:ring-orange-500">
                <option value="latest">Novedades</option>
                <option value="price_asc">Precio: Menor a Mayor</option>
                <option value="price_desc">Precio: Mayor a Menor</option>
                <option value="name_asc">Nombre: A - Z</option>
                <option value="name_desc">Nombre: Z - A</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Products Loading/List container -->
        <div class="relative">
          
          <!-- Loading Overlay Skeletons -->
          <div wire:loading.delay class="absolute inset-0 bg-gray-50/50 dark:bg-neutral-900/50 z-10 rounded-2xl flex items-center justify-center">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full opacity-60">
              @for ($i = 0; $i < 6; $i++)
                <div class="animate-pulse bg-white border border-gray-150 rounded-2xl overflow-hidden h-[360px] p-5 space-y-4 dark:bg-neutral-900 dark:border-neutral-800">
                  <div class="bg-gray-200 dark:bg-neutral-800 aspect-square rounded-xl"></div>
                  <div class="h-4 bg-gray-200 dark:bg-neutral-800 rounded w-1/3"></div>
                  <div class="h-4 bg-gray-200 dark:bg-neutral-800 rounded w-3/4"></div>
                  <div class="h-4 bg-gray-200 dark:bg-neutral-800 rounded w-1/2"></div>
                </div>
              @endfor
            </div>
          </div>

          <!-- Product Grid -->
          @if ($products->isEmpty())
            <div class="bg-white border border-gray-150 rounded-2xl p-16 text-center dark:bg-neutral-850 dark:border-neutral-800">
              <div class="p-4 bg-orange-50 dark:bg-orange-950/20 text-orange-600 dark:text-orange-500 rounded-full inline-block mb-4">
                <svg class="size-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-gray-900 dark:text-white">No encontramos productos</h3>
              <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1 max-w-sm mx-auto">Prueba ajustando los filtros de búsqueda o categoría.</p>
              <button type="button" wire:click="resetFilters" class="mt-6 p-2 px-4 text-xs font-bold bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition shadow-sm">
                Limpiar todos los filtros
              </button>
            </div>
          @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
              @foreach ($products as $product)
                <div class="group flex flex-col bg-white border border-gray-150 rounded-2xl overflow-hidden hover:shadow-lg transition dark:bg-neutral-850 dark:border-neutral-800">
                  <!-- Image Wrapper -->
                  <div class="aspect-square bg-gray-50 dark:bg-neutral-800 flex items-center justify-center relative overflow-hidden">
                    @if (!empty($product->image_url))
                      <img src="{{ $product->image_url[0] }}" alt="{{ $product->name }}" class="object-cover size-full group-hover:scale-105 transition-transform duration-300">
                    @else
                      <svg class="size-12 text-gray-300 dark:text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                      </svg>
                    @endif

                    @if ($product->stock <= 0)
                      <span class="absolute top-3 left-3 bg-red-650 text-white text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md shadow-sm">Agotado</span>
                    @endif
                  </div>

                  <!-- Details -->
                  <div class="p-5 flex-1 flex flex-col">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">
                      {{ $product->category ? $product->category->name : 'General' }}
                    </span>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white group-hover:text-orange-600 transition-colors line-clamp-2 pr-1 mb-2">
                      {{ $product->name }}
                    </h3>
                    
                    <div class="flex items-baseline gap-2 mb-4 mt-auto">
                      <span class="text-lg font-extrabold font-title text-orange-600 dark:text-orange-500">${{ number_format($product->price, 0, ',', '.') }}</span>
                      @if ($product->compare_at_price)
                        <span class="text-xs text-neutral-400 line-through">${{ number_format($product->compare_at_price, 0, ',', '.') }}</span>
                      @endif
                    </div>

                    <!-- Add to Cart -->
                    <button type="button" 
                            wire:click="$dispatch('addToCart', { productId: {{ $product->id }} })" 
                            @if ($product->stock <= 0) disabled @endif
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-neutral-900 dark:bg-neutral-800 hover:bg-orange-600 dark:hover:bg-orange-600 text-white p-2.5 text-xs font-bold transition-all duration-150 disabled:opacity-50 disabled:pointer-events-none">
                      <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                      </svg>
                      Agregar al carro
                    </button>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Pagination links -->
            @if ($products->hasPages())
              <div class="flex items-center justify-center gap-2 pt-8 pb-4 border-t border-gray-100 dark:border-neutral-800">
                {{-- Previous Page Link --}}
                @if ($products->onFirstPage())
                  <button type="button" disabled class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 dark:border-neutral-800 bg-gray-50 text-gray-400 dark:bg-neutral-800 dark:text-neutral-600 opacity-50 cursor-not-allowed">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                @else
                  <button type="button" wire:click="previousPage" class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 dark:border-neutral-800 bg-white text-gray-700 dark:bg-neutral-800 dark:text-neutral-350 hover:bg-orange-600 hover:text-white dark:hover:bg-orange-600 dark:hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                @endif

                {{-- Page Numbers --}}
                @php
                  $currentPage = $products->currentPage();
                  $lastPage = $products->lastPage();
                  $delta = 1;
                  $range = [];
                  for ($i = 1; $i <= $lastPage; $i++) {
                      if ($i === 1 || $i === $lastPage || ($i >= $currentPage - $delta && $i <= $currentPage + $delta)) {
                          $range[] = $i;
                      } elseif (end($range) !== '...') {
                          $range[] = '...';
                      }
                  }
                @endphp

                @foreach ($range as $page)
                  @if ($page === '...')
                    <span class="flex h-10 w-10 items-center justify-center text-gray-400 dark:text-neutral-600 text-sm">
                      ...
                    </span>
                  @elseif ($page == $currentPage)
                    <button type="button" disabled class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-extrabold bg-orange-600 text-white shadow-lg shadow-orange-600/30 scale-110">
                      {{ $page }}
                    </button>
                  @else
                    <button type="button" wire:click="gotoPage({{ $page }})" class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold border border-gray-200 dark:border-neutral-800 bg-white text-gray-700 dark:bg-neutral-800 dark:text-neutral-350 hover:bg-orange-600 hover:text-white dark:hover:bg-orange-600 dark:hover:text-white transition-colors">
                      {{ $page }}
                    </button>
                  @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($products->hasMorePages())
                  <button type="button" wire:click="nextPage" class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 dark:border-neutral-800 bg-white text-gray-700 dark:bg-neutral-800 dark:text-neutral-350 hover:bg-orange-600 hover:text-white dark:hover:bg-orange-600 dark:hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                @else
                  <button type="button" disabled class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 dark:border-neutral-800 bg-gray-50 text-gray-400 dark:bg-neutral-800 dark:text-neutral-600 opacity-50 cursor-not-allowed">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                @endif
              </div>
            @endif
          @endif

        </div>

      </div>

    </div>
  </div>

  <!-- Mobile Filters Slide-over Drawer -->
  <div x-show="isFiltersOpen" class="fixed inset-0 z-50 flex justify-end lg:hidden" style="display: none;" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="isFiltersOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="isFiltersOpen = false"
         class="fixed inset-0 bg-[#070b13]/60 backdrop-blur-xs"></div>

    <!-- Drawer Content -->
    <div x-show="isFiltersOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white dark:bg-neutral-900 py-6 px-6 shadow-2xl border-l border-gray-150 dark:border-neutral-800">
      
      <!-- Header -->
      <div class="flex items-center justify-between pb-4 border-b border-gray-150 dark:border-neutral-800">
        <h2 class="text-base font-extrabold text-gray-900 dark:text-white uppercase tracking-wider font-title">Filtros</h2>
        <button type="button" @click="isFiltersOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
          <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Filters List -->
      <div class="py-6 space-y-6">
        <!-- Categories -->
        <div>
          <h3 class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-3">Categorías</h3>
          <div class="space-y-1.5 max-h-[45vh] overflow-y-auto pr-1">
            <button type="button" 
                    wire:click="filterCategory(''); isFiltersOpen = false;" 
                    class="w-full flex items-center justify-between text-sm py-2 rounded-xl text-left {{ $categorySlug === '' ? 'bg-orange-50 text-orange-600 dark:bg-orange-950/20 dark:text-orange-400 font-semibold' : 'text-gray-700 hover:bg-gray-50 dark:text-neutral-400 dark:hover:bg-neutral-800' }}">
              <span>Todas las categorías</span>
            </button>
            @foreach ($categories as $cat)
              <button type="button" 
                      wire:click="filterCategory('{{ $cat->slug }}'); isFiltersOpen = false;" 
                      class="w-full flex items-center justify-between text-sm py-2 rounded-xl text-left {{ $categorySlug === $cat->slug ? 'bg-orange-50 text-orange-600 dark:bg-orange-950/20 dark:text-orange-400 font-semibold' : 'text-gray-700 hover:bg-gray-50 dark:text-neutral-400 dark:hover:bg-neutral-800' }}">
                <span class="truncate">{{ $cat->name }}</span>
                <span class="text-xs bg-gray-100 text-gray-500 py-0.5 px-2 rounded-full dark:bg-neutral-800 dark:text-neutral-400 shrink-0">{{ $cat->products_count }}</span>
              </button>
            @endforeach
          </div>
        </div>

        <!-- Price Range -->
        <div>
          <h3 class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-3">Rango de Precios</h3>
          <div class="space-y-3">
            <div class="flex gap-2">
              <input type="number" wire:model="minPrice" placeholder="Mín" class="w-full text-xs rounded-xl border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500">
              <input type="number" wire:model="maxPrice" placeholder="Máx" class="w-full text-xs rounded-xl border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500">
            </div>
            <button type="button" wire:click="applyPriceFilter(); isFiltersOpen = false;" class="w-full py-2.5 text-xs font-bold bg-neutral-900 dark:bg-neutral-800 hover:bg-orange-600 text-white rounded-xl transition shadow-xs">
              Aplicar Filtro
            </button>
          </div>
        </div>

        <!-- Reset -->
        <button type="button" wire:click="resetFilters(); isFiltersOpen = false;" class="w-full py-3 text-xs font-bold border border-gray-200 hover:bg-gray-50 text-gray-600 dark:border-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-800 rounded-xl transition">
          Limpiar Filtros
        </button>
      </div>
    </div>
  </div>
</div>
