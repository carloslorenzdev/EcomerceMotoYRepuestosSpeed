<div class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-data="{ open: @entangle('isOpen') }" x-show="open" style="display: none;">
  <!-- Background backdrop, show/hide based on slide-over state. -->
  <div class="fixed inset-0 bg-neutral-900/60 backdrop-blur-xs transition-opacity" 
       x-show="open" 
       x-transition:enter="ease-in-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="ease-in-out duration-300"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       wire:click="closeCart"></div>

  <div class="fixed inset-0 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
        <!-- Slide-over panel -->
        <div class="pointer-events-auto w-screen max-w-md transform transition-all duration-300 sm:duration-500" 
             x-show="open"
             x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
          
          <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl dark:bg-neutral-850">
            <!-- Header -->
            <div class="flex items-start justify-between p-6 border-b border-gray-150 dark:border-neutral-750">
              <h2 class="text-lg font-bold font-title text-gray-900 dark:text-white" id="slide-over-title">Tu Carrito de Compras</h2>
              <div class="ml-3 flex h-7 items-center">
                <button type="button" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500 dark:hover:text-neutral-300" wire:click="closeCart">
                  <span class="absolute -inset-0.5"></span>
                  <span class="sr-only">Cerrar carrito</span>
                  <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Content -->
            <div class="flex-1 px-6 py-4">
              @if (empty($cartItems))
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-20 text-center">
                  <div class="p-4 bg-orange-50 dark:bg-orange-950/20 text-orange-600 dark:text-orange-500 rounded-full mb-4">
                    <svg class="size-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                  </div>
                  <h3 class="text-base font-semibold text-gray-800 dark:text-neutral-200">El carro está vacío</h3>
                  <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Aún no has agregado repuestos ni accesorios.</p>
                  <button type="button" wire:click="closeCart" class="mt-6 inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg bg-orange-600 hover:bg-orange-700 text-white shadow-sm">
                    Ir a la tienda
                  </button>
                </div>
              @else
                <!-- Items list -->
                <div class="space-y-4">
                  <div class="flow-root">
                    <ul role="list" class="-my-6 divide-y divide-gray-100 dark:divide-neutral-750">
                      @foreach ($cartItems as $productId => $item)
                        <li class="flex py-6">
                          <div class="size-16 shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-800 flex items-center justify-center">
                            @if ($item['image'])
                              <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="size-full object-cover object-center">
                            @else
                              <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                              </svg>
                            @endif
                          </div>

                          <div class="ml-4 flex flex-1 flex-col">
                            <div>
                              <div class="flex justify-between text-sm font-semibold text-gray-900 dark:text-white">
                                <h3 class="line-clamp-2 pr-2">
                                  <a href="{{ route('shop') }}?search={{ $item['sku'] }}">{{ $item['name'] }}</a>
                                </h3>
                                <p class="ml-4 text-orange-600 dark:text-orange-500 font-title">${{ number_format($item['price'], 0, ',', '.') }}</p>
                              </div>
                              <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">SKU: {{ $item['sku'] }}</p>
                            </div>
                            <div class="flex flex-1 items-end justify-between text-sm">
                              <!-- Quantity selectors -->
                              <div class="flex items-center border border-gray-200 dark:border-neutral-700 rounded-md">
                                <button type="button" wire:click="decrementQuantity({{ $productId }})" class="p-1 px-2 text-gray-500 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500">-</button>
                                <span class="px-2 text-xs font-semibold dark:text-white">{{ $item['quantity'] }}</span>
                                <button type="button" wire:click="incrementQuantity({{ $productId }})" class="p-1 px-2 text-gray-500 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500">+</button>
                              </div>

                              <div class="flex">
                                <button type="button" wire:click="removeItem({{ $productId }})" class="font-medium text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300">Quitar</button>
                              </div>
                            </div>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>

                <!-- Checkout Form -->
                <div class="mt-8 border-t border-gray-150 dark:border-neutral-750 pt-6">
                  <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Datos de Entrega / Pago</h3>
                  
                  <form wire:submit.prevent="checkout" class="space-y-4">
                    <div>
                      <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Nombre Completo</label>
                      <input type="text" wire:model="customer_name" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="Juan Pérez">
                      @error('customer_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Email</label>
                        <input type="email" wire:model="customer_email" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="juan@gmail.com">
                        @error('customer_email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                      </div>
                      <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Teléfono</label>
                        <input type="text" wire:model="customer_phone" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="+56912345678">
                        @error('customer_phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                      </div>
                    </div>

                    <div>
                      <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Dirección (Calle y Número)</label>
                      <input type="text" wire:model="address_street" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="Av. Libertador 123">
                      @error('address_street') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Region/Provincia/Comuna Selectors -->
                    @if (!$manual_mode)
                      <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Región</label>
                        <select wire:model.live="selected_region" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500">
                          <option value="">Selecciona tu región</option>
                          @foreach ($regions as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                          @endforeach
                        </select>
                        @error('selected_region') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                      </div>

                      @if ($selected_region)
                        <div>
                          <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Provincia</label>
                          <select wire:model.live="selected_provincia" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500">
                            <option value="">Selecciona tu provincia</option>
                            @foreach ($provinces as $p)
                              <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                          </select>
                          @error('selected_provincia') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                      @endif

                      @if ($selected_provincia)
                        <div>
                          <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Comuna / Ciudad</label>
                          <select wire:model.live="selected_commune" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500">
                            <option value="">Selecciona tu comuna</option>
                            @foreach ($communes as $c)
                              <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                          </select>
                          @error('selected_commune') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                      @endif

                      <div class="pt-1">
                        <button type="button" wire:click="$set('manual_mode', true)" class="text-xs font-semibold text-orange-600 hover:text-orange-500 dark:text-orange-500 dark:hover:text-orange-400">
                          No aparece mi comuna · ingresar manualmente
                        </button>
                      </div>
                    @else
                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Comuna</label>
                          <input type="text" wire:model="address_commune" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="Rancagua">
                          @error('address_commune') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                          <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Ciudad</label>
                          <input type="text" wire:model="address_city" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="Rancagua">
                          @error('address_city') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                      </div>

                      <div class="pt-1">
                        <button type="button" wire:click="$set('manual_mode', false)" class="text-xs font-semibold text-orange-600 hover:text-orange-500 dark:text-orange-500 dark:hover:text-orange-400">
                          Usar selectores automáticos
                        </button>
                      </div>
                    @endif
                  </form>
                </div>
              @endif
            </div>

            <!-- Footer / Totals and checkout button -->
            @if (!empty($cartItems))
              <div class="border-t border-gray-150 dark:border-neutral-750 p-6 bg-gray-50 dark:bg-neutral-800/40">
                <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white mb-2">
                  <p>Subtotal</p>
                  <p class="font-title text-orange-600 dark:text-orange-500">${{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <p class="text-xs text-gray-500 dark:text-neutral-400 mb-6 leading-relaxed">
                  Envío coordinado posterior a la compra. Los pagos se procesan con Mercado Pago de forma segura.
                </p>
                <div>
                  <button type="button" wire:click="checkout" wire:loading.attr="disabled" class="w-full flex items-center justify-center gap-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white p-3.5 font-bold shadow-md transition-colors duration-150 disabled:opacity-50">
                    <svg wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Pagar con Mercado Pago
                  </button>
                </div>
                <div class="mt-6 flex justify-center text-center text-xs text-gray-500">
                  <p>
                    o
                    <button type="button" class="font-medium text-orange-600 hover:text-orange-500" wire:click="closeCart">
                      Continuar Comprando
                      <span aria-hidden="true"> &rarr;</span>
                    </button>
                  </p>
                </div>
              </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
