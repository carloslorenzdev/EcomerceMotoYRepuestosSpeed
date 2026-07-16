<div>
  <!-- Hero Section -->
  <section class="relative w-full bg-[#0B0F19] text-white overflow-hidden min-h-[600px] flex items-center py-20 lg:py-32">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat opacity-30" style="background-image: url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?q=80&w=2070&auto=format&fit=crop');"></div>
    <!-- Gradient Overlay for blending -->
    <div class="absolute inset-0 z-0 bg-gradient-to-r from-[#0B0F19] via-[#0B0F19]/80 to-transparent"></div>
    <div class="absolute inset-0 z-0 bg-gradient-to-t from-[#0B0F19] via-transparent to-[#0B0F19]/50"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl space-y-6">
        <div class="flex items-center gap-4 mb-4">
          <div class="h-[1px] w-8 bg-orange-600"></div>
          <span class="text-sm font-bold tracking-widest text-slate-350 uppercase">MOTOS Y REPUESTOS</span>
          <div class="h-[1px] w-8 bg-orange-600"></div>
        </div>

        <h1 class="text-5xl sm:text-6xl md:text-7xl font-black leading-[1.1] tracking-tight uppercase mb-6">
          TALLER,<br />
          REPUESTOS<br />
          Y RESCATE<br />
          DE MOTOS<br />
          <span class="bg-orange-600 text-white px-2.5 mt-2 inline-block">EN RANCAGUA</span>
        </h1>

        <p class="text-lg text-slate-300 mb-8 max-w-md">
          Soluciones completas para tu moto. Rápido, seguro y confiable.
        </p>

        <ul class="space-y-3 mb-10">
          <li class="flex items-center gap-3 text-sm font-semibold text-slate-200">
            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Distribuidores oficiales Bajaj
          </li>
          <li class="flex items-center gap-3 text-sm font-semibold text-slate-200">
            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Repuestos y accesorios originales
          </li>
          <li class="flex items-center gap-3 text-sm font-semibold text-slate-200">
            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Taller especializado
          </li>
          <li class="flex items-center gap-3 text-sm font-semibold text-slate-200">
            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Rescate y traslado de motos
          </li>
        </ul>

        <div class="flex flex-wrap items-center gap-4 pt-2">
          <a href="{{ route('shop') }}" class="inline-flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-8 py-3.5 rounded-lg text-sm font-bold tracking-wide uppercase transition-colors" wire:navigate>
            Explorar Tienda
          </a>
          <a href="{{ route('services') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-neutral-600 text-white rounded-lg text-sm font-bold tracking-wide uppercase hover:border-neutral-400 hover:bg-neutral-800 transition-colors" wire:navigate>
            Ver Servicios
          </a>
        </div>
      </div>
    </div>

    <!-- Floating Badge (Desktop) -->
    <div class="hidden lg:flex absolute right-16 top-1/2 -translate-y-1/2 bg-[#1A1F2E]/80 backdrop-blur-md border border-slate-700/50 rounded-xl p-4 items-center gap-4 shadow-2xl">
      <div class="w-10 h-10 rounded-full bg-orange-600/20 flex items-center justify-center text-orange-500">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
      </div>
      <div>
        <p class="text-xs font-bold uppercase text-slate-200">Rescate de motos</p>
        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Rápido, seguro y confiable</p>
      </div>
    </div>
  </section>

  <!-- Services Summary Section -->
  <section class="py-20 bg-white dark:bg-neutral-900">
    <div class="max-w-[85rem] mx-auto px-4">
      <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold text-orange-600 dark:text-orange-500 uppercase tracking-widest">Lo que hacemos</span>
        <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white font-title">Nuestros Servicios Especializados</h2>
        <p class="text-neutral-500 dark:text-neutral-400">Brindamos una gama completa de servicios para mantener tu motocicleta en perfectas condiciones.</p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Service Card: Taller -->
        <div class="group flex flex-col h-full bg-gray-50 border border-gray-150 hover:border-orange-500/30 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 dark:hover:border-orange-500/30 transition-all duration-300">
          <div class="size-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center dark:bg-orange-950/20 dark:text-orange-500 mb-5">
            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Taller Mecánico</h3>
          <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed mb-4">
            Reparación de motores, transmisiones, suspensión y diagnóstico computarizado para fallas complejas.
          </p>
          <a href="{{ route('services') }}" class="mt-auto text-sm font-bold text-orange-600 dark:text-orange-500 hover:text-orange-700 dark:hover:text-orange-400 flex items-center gap-1" wire:navigate>
            Saber más &rarr;
          </a>
        </div>

        <!-- Service Card: Mantención -->
        <div class="group flex flex-col h-full bg-gray-50 border border-gray-150 hover:border-orange-500/30 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 dark:hover:border-orange-500/30 transition-all duration-300">
          <div class="size-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center dark:bg-orange-950/20 dark:text-orange-500 mb-5">
            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Mantención por Pauta</h3>
          <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed mb-4">
            Ajustes según kilometraje para conservar la garantía de tu moto y garantizar tu seguridad en ruta.
          </p>
          <a href="{{ route('services') }}" class="mt-auto text-sm font-bold text-orange-600 dark:text-orange-500 hover:text-orange-700 dark:hover:text-orange-400 flex items-center gap-1" wire:navigate>
            Saber más &rarr;
          </a>
        </div>

        <!-- Service Card: Rescate -->
        <div class="group flex flex-col h-full bg-gray-50 border border-gray-150 hover:border-orange-500/30 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 dark:hover:border-orange-500/30 transition-all duration-300">
          <div class="size-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center dark:bg-orange-950/20 dark:text-orange-500 mb-5">
            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Rescate y Traslado</h3>
          <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed mb-4">
            Servicio rápido de transporte de motos en Rancagua y alrededores para cuando quedas en pana.
          </p>
          <a href="{{ route('services') }}" class="mt-auto text-sm font-bold text-orange-600 dark:text-orange-500 hover:text-orange-700 dark:hover:text-orange-400 flex items-center gap-1" wire:navigate>
            Saber más &rarr;
          </a>
        </div>

        <!-- Service Card: Repuestos -->
        <div class="group flex flex-col h-full bg-gray-50 border border-gray-150 hover:border-orange-500/30 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 dark:hover:border-orange-500/30 transition-all duration-300">
          <div class="size-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center dark:bg-orange-950/20 dark:text-orange-500 mb-5">
            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Repuestos Originales</h3>
          <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed mb-4">
            Acceso directo a catálogo de piezas oficiales y de alta gama homologadas para diversas marcas.
          </p>
          <a href="{{ route('shop') }}" class="mt-auto text-sm font-bold text-orange-600 dark:text-orange-500 hover:text-orange-700 dark:hover:text-orange-400 flex items-center gap-1" wire:navigate>
            Ver Tienda &rarr;
          </a>
        </div>

      </div>
    </div>
  </section>

  <!-- Bajaj Distributor Section -->
  <section class="py-20 bg-[#0B0F19] text-white overflow-hidden relative">
    <!-- background lighting -->
    <div class="absolute -bottom-10 right-0 translate-x-20 blur-3xl opacity-20 size-[300px] bg-orange-500 rounded-full"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-[#111726]/80 backdrop-blur-xl rounded-3xl p-8 md:p-12 lg:p-16 border border-slate-800/50 flex flex-col lg:flex-row gap-12 items-center justify-between">
        
        <!-- Left Content -->
        <div class="flex-1 space-y-6">
          <div class="w-20 h-20 bg-white rounded-xl flex items-center justify-center p-3 mb-8 shadow-lg">
            <img 
              src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2b/Bajaj_Auto_Ltd_logo.svg/1280px-Bajaj_Auto_Ltd_logo.svg.png" 
              alt="Bajaj Logo" 
              class="w-full h-full object-contain"
            />
          </div>

          <div>
            <h2 class="text-3xl font-black text-white uppercase tracking-tight">
              DISTRIBUIDORES OFICIALES<br/>
              <span class="text-[#0052A3]">BAJAJ</span>
            </h2>
          </div>

          <ul class="space-y-3 mt-6">
            <li class="flex items-center gap-3 text-sm text-slate-300">
              <div class="w-5 h-5 rounded-full border border-[#0052A3] flex items-center justify-center text-[#0052A3] shrink-0">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              Repuestos originales Bajaj
            </li>
            <li class="flex items-center gap-3 text-sm text-slate-300">
              <div class="w-5 h-5 rounded-full border border-[#0052A3] flex items-center justify-center text-[#0052A3] shrink-0">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              Calidad garantizada
            </li>
            <li class="flex items-center gap-3 text-sm text-slate-300">
              <div class="w-5 h-5 rounded-full border border-[#0052A3] flex items-center justify-center text-[#0052A3] shrink-0">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              Rendimiento para tu moto
            </li>
          </ul>
        </div>

        <!-- Right Images -->
        <div class="flex-1 flex gap-4 w-full">
          <div class="flex-1 rounded-2xl overflow-hidden bg-slate-800 aspect-[3/4] border border-slate-700 shadow-xl">
            <img 
              src="https://cdn.bajajauto.com/es-ve/-/media/globalbajajauto/common-media/product-detail-page-banners/latam/pulsar-n250.webp" 
              alt="Bajaj Pulsar N250" 
              class="w-full h-full object-cover" 
            />
          </div>

          <div class="flex-1 rounded-2xl overflow-hidden bg-slate-800 aspect-[3/4] border border-slate-700 shadow-xl mt-8">
            <img 
              src="https://cdn.bajajauto.com/es-ve/-/media/globalbajajauto/common-media/product-detail-page-banners/latam/pulsar-n160.webp" 
              alt="Bajaj Pulsar N160" 
              class="w-full h-full object-cover" 
            />
          </div>
        </div>
        
      </div>
    </div>
  </section>

  <!-- Featured Products Section -->
  <section class="py-20 bg-gray-50 dark:bg-neutral-950">
    <div class="max-w-[85rem] mx-auto px-4">
      <div class="flex flex-col sm:flex-row items-end justify-between mb-12 gap-4">
        <div class="space-y-2 max-w-xl">
          <span class="text-xs font-bold text-orange-600 dark:text-orange-500 uppercase tracking-widest">Lo más buscado</span>
          <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white font-title">Productos Destacados</h2>
          <p class="text-sm text-neutral-500 dark:text-neutral-400">Explora repuestos premium listos para tu moto.</p>
        </div>
        <a href="{{ route('shop') }}" class="text-sm font-bold text-orange-600 dark:text-orange-500 hover:text-orange-700 dark:hover:text-orange-400 flex items-center gap-1.5 shrink-0" wire:navigate>
          Ver todo el catálogo
          <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>

      <!-- Products Grid -->
      @if ($featuredProducts->isEmpty())
        <div class="bg-white border border-gray-150 rounded-2xl p-12 text-center dark:bg-neutral-900 dark:border-neutral-800">
          <p class="text-gray-500 dark:text-neutral-400">No hay productos destacados cargados en este momento.</p>
          <p class="text-xs text-gray-400 mt-1">Los productos sincronizados desde RelBase aparecerán automáticamente aquí.</p>
        </div>
      @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
          @foreach ($featuredProducts as $product)
            <div class="group flex flex-col bg-white border border-gray-150 rounded-2xl overflow-hidden hover:shadow-lg transition dark:bg-neutral-900 dark:border-neutral-800">
              
              <!-- Product Image -->
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

              <!-- Content details -->
              <div class="p-5 flex-1 flex flex-col">
                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">
                  {{ $product->category ? $product->category->name : 'General' }}
                </span>
                <h3 class="text-sm font-bold text-gray-800 dark:text-white group-hover:text-orange-650 transition-colors line-clamp-2 pr-1 mb-2">
                  {{ $product->name }}
                </h3>
                
                <div class="flex items-baseline gap-2 mb-4 mt-auto">
                  <span class="text-lg font-extrabold font-title text-orange-650 dark:text-orange-500">${{ number_format($product->price, 0, ',', '.') }}</span>
                  @if ($product->compare_at_price)
                    <span class="text-xs text-neutral-450 line-through">${{ number_format($product->compare_at_price, 0, ',', '.') }}</span>
                  @endif
                </div>

                <!-- Action Button -->
                <button type="button" 
                        wire:click="$dispatch('addToCart', { productId: {{ $product->id }} })" 
                        @if ($product->stock <= 0) disabled @endif
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-neutral-900 dark:bg-neutral-800 hover:bg-orange-600 dark:hover:bg-orange-600 hover:text-white text-white dark:text-white p-2.5 text-xs font-bold transition-all duration-150 disabled:opacity-50 disabled:pointer-events-none">
                  <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                  </svg>
                  Agregar al carro
                </button>
              </div>

            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  @if(false)
  <!-- Testimonials Section -->
  <section class="py-20 bg-white dark:bg-neutral-900">
    <div class="max-w-[85rem] mx-auto px-4">
      <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold text-orange-600 dark:text-orange-500 uppercase tracking-widest">Opiniones</span>
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white font-title">Lo que dicen nuestros clientes</h2>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">La experiencia de quienes confían el cuidado de sus motocicletas en nuestro taller.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        
        <!-- Card 1 -->
        <div class="bg-gray-50 border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800">
          <div class="flex gap-1 text-orange-500 mb-4">
            @for ($i = 0; $i < 5; $i++)
              <svg class="size-4.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
          </div>
          <p class="text-sm text-neutral-600 dark:text-neutral-300 italic leading-relaxed mb-6">
            "Excelente atención y rapidez. Llevé mi Pulsar NS200 para la mantención de los 15.000 km y quedó impecable. Los repuestos son 100% originales."
          </p>
          <div class="flex items-center gap-3">
            <span class="flex items-center justify-center size-9 rounded-full bg-orange-100 text-orange-800 font-bold text-xs dark:bg-orange-950/40 dark:text-orange-400">
              CL
            </span>
            <div>
              <h4 class="text-sm font-bold text-gray-800 dark:text-white">Carlos Lorenz</h4>
              <p class="text-xs text-neutral-400">Rancagua</p>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-gray-50 border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800">
          <div class="flex gap-1 text-orange-500 mb-4">
            @for ($i = 0; $i < 5; $i++)
              <svg class="size-4.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
          </div>
          <p class="text-sm text-neutral-600 dark:text-neutral-300 italic leading-relaxed mb-6">
            "Tuve una pana en Av. España y llamé al servicio de rescate de MotosSpeed. Llegaron super rápido con la camioneta equipada y la trasladaron directamente al taller."
          </p>
          <div class="flex items-center gap-3">
            <span class="flex items-center justify-center size-9 rounded-full bg-orange-100 text-orange-800 font-bold text-xs dark:bg-orange-950/40 dark:text-orange-400">
              MV
            </span>
            <div>
              <h4 class="text-sm font-bold text-gray-800 dark:text-white">Mario Valenzuela</h4>
              <p class="text-xs text-neutral-400">Machalí</p>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-gray-50 border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800">
          <div class="flex gap-1 text-orange-500 mb-4">
            @for ($i = 0; $i < 5; $i++)
              <svg class="size-4.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
          </div>
          <p class="text-sm text-neutral-600 dark:text-neutral-300 italic leading-relaxed mb-6">
            "Compré un filtro de aceite y pastillas de freno en su sitio web. Todo el proceso fue muy fácil y lo retiré en la sucursal de Av. Illanes sin demoras. Recomendado!"
          </p>
          <div class="flex items-center gap-3">
            <span class="flex items-center justify-center size-9 rounded-full bg-orange-100 text-orange-800 font-bold text-xs dark:bg-orange-950/40 dark:text-orange-400">
              FS
            </span>
            <div>
              <h4 class="text-sm font-bold text-gray-800 dark:text-white">Francisco Silva</h4>
              <p class="text-xs text-neutral-400">Rancagua</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  @endif
</div>
