@push('meta')
    @php
        $seoDesc = Str::limit(strip_tags($product->description ?? 'Compra '.$product->name.' en Motos y Repuestos Speed.'), 160);
    @endphp
    <meta property="og:title" content="{{ $product->name }} | Motos y Repuestos Speed" />
    <meta property="og:description" content="{{ $seoDesc }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="https://www.motosspeed.cl/producto/{{ $product->slug }}" />
    <link rel="canonical" href="https://www.motosspeed.cl/producto/{{ $product->slug }}" />
    @if(!empty($product->visible_images))
        <meta property="og:image" content="{{ $product->visible_images[0] }}" />
    @endif
    
    <!-- JSON-LD Structured Data for Google -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Product",
      "name": "{{ $product->name }}",
      "image": {!! json_encode($product->visible_images) !!},
      "description": "{{ $seoDesc }}",
      "sku": "{{ $product->sku }}",
      "offers": {
        "@@type": "Offer",
        "url": "{{ request()->url() }}",
        "priceCurrency": "CLP",
        "price": "{{ $product->price }}",
        "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition"
      }
    }
    </script>
@endpush

@section('title', $product->name)
@section('meta_description', $seoDesc)

<div>
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300' }}">
            <span class="font-bold mr-2">{{ session('toast')['type'] === 'success' ? 'Éxito:' : 'Error:' }}</span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <!-- Breadcrumb -->
    <div class="bg-neutral-900 text-white py-6 border-b border-neutral-800">
        <div class="max-w-[85rem] mx-auto px-4 flex items-center gap-2 text-sm text-neutral-400">
            <a href="{{ route('home') }}" class="hover:text-white transition">Inicio</a>
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <a href="{{ route('shop') }}" class="hover:text-white transition">Tienda</a>
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-orange-500 font-bold truncate max-w-xs">{{ $product->name }}</span>
        </div>
    </div>

    <div class="max-w-[85rem] mx-auto px-4 py-12">
        <div class="bg-white dark:bg-neutral-850 border border-gray-200 dark:border-neutral-800 rounded-3xl p-6 lg:p-10 shadow-sm">
            <div class="grid lg:grid-cols-2 gap-12 items-start">
                
                <!-- Left: Image Gallery & Zoom -->
                <div class="flex flex-col gap-4">
                    <!-- Main Image with Zoom effect -->
                    @if(!empty($product->visible_images))
                        @php $activeImage = $product->visible_images[$activeImageIndex] ?? $product->visible_images[0]; @endphp
                        <div x-data="{ zoom: false, x: 0, y: 0 }" 
                             @mousemove="if(zoom) { x = ($event.offsetX / $event.target.offsetWidth) * 100; y = ($event.offsetY / $event.target.offsetHeight) * 100 }"
                             @click="zoom = !zoom" 
                             @mouseleave="zoom = false"
                             :class="zoom ? 'cursor-zoom-out' : 'cursor-zoom-in'"
                             class="relative w-full aspect-square bg-gray-50 dark:bg-neutral-900 rounded-2xl overflow-hidden border border-gray-150 dark:border-neutral-800">
                            
                            <!-- Base Image -->
                            <img :class="zoom ? 'opacity-0' : 'opacity-100'" 
                                 src="{{ $activeImage }}" 
                                 alt="{{ $product->name }}" 
                                 class="absolute inset-0 w-full h-full object-contain transition-opacity duration-200 p-4">
                            
                            <!-- Zoom Overlay -->
                            <div x-show="zoom" 
                                 class="absolute inset-0 pointer-events-none bg-no-repeat bg-white dark:bg-neutral-900" 
                                 :style="`background-image: url('{{ $activeImage }}'); background-size: 200%; background-position: ${x}% ${y}%;`">
                            </div>
                        </div>
                        
                        <!-- Thumbnails Gallery -->
                        @if(count($product->visible_images) > 1)
                            <div class="grid grid-cols-5 gap-3 mt-2">
                                @foreach($product->visible_images as $index => $url)
                                    <button type="button" wire:click="setActiveImage({{ $index }})" 
                                            class="aspect-square rounded-xl overflow-hidden border-2 transition-all {{ $activeImageIndex === $index ? 'border-orange-500 opacity-100 ring-2 ring-orange-500/20' : 'border-gray-200 dark:border-neutral-800 opacity-60 hover:opacity-100 hover:border-gray-300 dark:hover:border-neutral-600' }}">
                                        <img src="{{ $url }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <!-- Fallback Image -->
                        <div class="w-full aspect-square bg-gray-50 dark:bg-neutral-900 rounded-2xl flex items-center justify-center border border-gray-150 dark:border-neutral-800">
                            <svg class="size-20 text-gray-300 dark:text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Right: Product Information -->
                <div class="flex flex-col h-full">
                    <span class="text-xs font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-2">
                        {{ $product->category->name ?? 'Repuestos' }}
                    </span>
                    <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white font-title leading-tight mb-4">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-150 dark:border-neutral-800">
                        <span class="text-xs font-mono bg-gray-100 dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 py-1.5 px-3 rounded-lg border border-gray-200 dark:border-neutral-700">
                            SKU: {{ $product->sku }}
                        </span>
                        
                        @if($product->stock > 5)
                            <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-3 py-1.5 rounded-lg">
                                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Stock Disponible ({{ $product->stock }})
                            </span>
                        @elseif($product->stock > 0)
                            <span class="flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30 px-3 py-1.5 rounded-lg">
                                <span class="size-2 rounded-full bg-amber-500"></span>
                                Últimas unidades ({{ $product->stock }})
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 px-3 py-1.5 rounded-lg">
                                <span class="size-2 rounded-full bg-red-500"></span>
                                Agotado
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col gap-1 mb-8">
                        @if ($product->compare_at_price)
                            <span class="text-sm text-neutral-400 line-through">Precio Normal: ${{ number_format($product->compare_at_price, 0, ',', '.') }}</span>
                        @endif
                        <span class="text-4xl font-black text-orange-600 dark:text-orange-500">
                            ${{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($product->description)
                        <div class="prose prose-sm dark:prose-invert prose-orange max-w-none text-gray-600 dark:text-neutral-400 mb-8">
                            <p>{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="mt-auto pt-6 border-t border-gray-150 dark:border-neutral-800">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Quantity Selector -->
                            <div class="flex items-center border border-gray-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-neutral-900 p-1 w-full sm:w-32">
                                <button type="button" wire:click="decrement" class="p-2 text-gray-500 hover:text-orange-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-800 rounded-lg transition-colors">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                                </button>
                                <span class="flex-1 text-center font-bold text-gray-900 dark:text-white">{{ $quantity }}</span>
                                <button type="button" wire:click="increment" class="p-2 text-gray-500 hover:text-orange-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-800 rounded-lg transition-colors">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
                            
                            <!-- Add to cart -->
                            <button type="button" 
                                    wire:click="addToCart" 
                                    @if ($product->stock <= 0) disabled @endif
                                    class="flex-1 inline-flex items-center justify-center gap-3 rounded-xl p-4 text-sm font-black transition-all duration-200 shadow-sm
                                    {{ $product->stock <= 0 ? 'bg-gray-200 text-gray-500 dark:bg-neutral-800 dark:text-neutral-500 cursor-not-allowed' : 'bg-orange-600 text-white hover:bg-orange-700 hover:shadow-orange-600/20 hover:shadow-xl active:scale-95' }}">
                                @if ($product->stock <= 0)
                                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    Agotado
                                @else
                                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    Agregar {{ $quantity }} al carro
                                @endif
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white font-title mb-6">Productos Relacionados</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="group flex flex-col bg-white border border-gray-150 rounded-2xl overflow-hidden hover:shadow-lg transition dark:bg-neutral-850 dark:border-neutral-800">
                            <div class="aspect-square bg-gray-50 dark:bg-neutral-800 flex items-center justify-center relative overflow-hidden">
                                @if (!empty($related->visible_images))
                                    <a href="{{ route('product.detail', $related->slug) }}" class="contents">
                                        <img src="{{ $related->visible_images[0] }}" alt="{{ $related->name }}" class="object-cover size-full group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                @else
                                    <a href="{{ route('product.detail', $related->slug) }}" class="contents">
                                        <svg class="size-10 text-gray-300 dark:text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </a>
                                @endif
                                @if ($related->stock <= 0)
                                    <span class="absolute top-2 left-2 bg-red-650 text-white text-[9px] font-extrabold uppercase px-2 py-1 rounded shadow-sm">Agotado</span>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <a href="{{ route('product.detail', $related->slug) }}" class="text-xs font-bold text-gray-800 dark:text-white group-hover:text-orange-600 transition-colors line-clamp-2 mb-2">
                                    {{ $related->name }}
                                </a>
                                <div class="mt-auto">
                                    <span class="font-extrabold text-orange-600 dark:text-orange-500">${{ number_format($related->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
