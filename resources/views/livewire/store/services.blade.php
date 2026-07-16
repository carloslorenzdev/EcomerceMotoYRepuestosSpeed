<div x-data x-on:scroll-to-booking.window="document.getElementById('booking-form').scrollIntoView({ behavior: 'smooth' })">
  <!-- Header Title -->
  <div class="bg-neutral-900 text-white py-12">
    <div class="max-w-[85rem] mx-auto px-4">
      <h1 class="text-3xl font-extrabold font-title">Servicios Técnicos Especializados</h1>
      <p class="text-sm text-neutral-400 mt-2">Mantenimiento calificado, reparaciones mecánicas y soporte local en Rancagua.</p>
    </div>
  </div>

  <!-- Services Grid & Booking -->
  <div class="max-w-[85rem] mx-auto px-4 py-16">
    <div class="grid lg:grid-cols-12 gap-12">
      
      <!-- Left side: Services List -->
      <div class="lg:col-span-7 space-y-8">
        <h2 class="text-2xl font-bold font-title text-gray-900 dark:text-white border-b border-gray-150 dark:border-neutral-800 pb-4">Nuestros Servicios Disponibles</h2>
        
        @if ($services->isEmpty())
          <!-- Default Mock services if DB is empty -->
          <div class="space-y-6">
            
            <!-- Service 1: Mantención -->
            <div class="bg-white border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 flex flex-col sm:flex-row gap-6">
              <div class="size-16 sm:size-20 shrink-0 bg-orange-100 dark:bg-orange-950/20 text-orange-600 dark:text-orange-500 rounded-2xl flex items-center justify-center">
                <svg class="size-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <div class="flex-grow space-y-2">
                <div class="flex justify-between items-start gap-4">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mantención Preventiva</h3>
                  <span class="text-orange-600 dark:text-orange-500 font-bold font-title shrink-0">Desde $45.000</span>
                </div>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">
                  Servicio completo que incluye cambio de aceite, limpieza de filtros, regulación de frenos, lubricación de cadena y diagnóstico general según la pauta de kilometraje.
                </p>
                <div class="flex items-center gap-4 pt-2 text-xs text-neutral-450 font-medium">
                  <span class="flex items-center gap-1">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Est. 90 mins
                  </span>
                </div>
              </div>
            </div>

            <!-- Service 2: Scanner -->
            <div class="bg-white border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 flex flex-col sm:flex-row gap-6">
              <div class="size-16 sm:size-20 shrink-0 bg-orange-100 dark:bg-orange-950/20 text-orange-600 dark:text-orange-500 rounded-2xl flex items-center justify-center">
                <svg class="size-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div class="flex-grow space-y-2">
                <div class="flex justify-between items-start gap-4">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Scanner y Diagnóstico Computarizado</h3>
                  <span class="text-orange-600 dark:text-orange-500 font-bold font-title shrink-0">$25.000</span>
                </div>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">
                  Lectura de códigos de error de la ECU, borrado de fallas, calibración de sensores de inyección electrónica con equipamiento compatible multimarca.
                </p>
                <div class="flex items-center gap-4 pt-2 text-xs text-neutral-450 font-medium">
                  <span class="flex items-center gap-1">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Est. 45 mins
                  </span>
                </div>
              </div>
            </div>

            <!-- Service 3: Rescate -->
            <div class="bg-white border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 flex flex-col sm:flex-row gap-6">
              <div class="size-16 sm:size-20 shrink-0 bg-orange-100 dark:bg-orange-950/20 text-orange-600 dark:text-orange-500 rounded-2xl flex items-center justify-center">
                <svg class="size-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div class="flex-grow space-y-2">
                <div class="flex justify-between items-start gap-4">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Rescate y Traslado en Camioneta</h3>
                  <span class="text-orange-600 dark:text-orange-500 font-bold font-title shrink-0">Según distancia</span>
                </div>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">
                  Recogemos tu moto en panne y la trasladamos de forma segura a nuestro taller mecánico en Av. Illanes. Disponible en toda la región de O'Higgins.
                </p>
                <div class="flex items-center gap-4 pt-2 text-xs text-neutral-450 font-medium">
                  <span class="flex items-center gap-1">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Rápida respuesta
                  </span>
                </div>
              </div>
            </div>

          </div>
        @else
          <!-- Render from database -->
          <div class="space-y-6">
            @foreach ($services as $service)
              <div class="bg-white border border-gray-150 rounded-2xl p-6 dark:bg-neutral-850 dark:border-neutral-800 flex flex-col sm:flex-row gap-6">
                <!-- Image/Icon -->
                <div class="size-16 sm:size-20 shrink-0 bg-gray-50 dark:bg-neutral-800 rounded-2xl overflow-hidden flex items-center justify-center">
                  @if ($service->image_url)
                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="object-cover size-full">
                  @else
                    <div class="text-orange-600 dark:text-orange-500">
                      <svg class="size-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                      </svg>
                    </div>
                  @endif
                </div>

                <div class="flex-grow space-y-2">
                  <div class="flex justify-between items-start gap-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $service->name }}</h3>
                    @if ($service->price)
                      <span class="text-orange-600 dark:text-orange-500 font-bold font-title shrink-0">${{ number_format($service->price, 0, ',', '.') }}</span>
                    @else
                      <span class="text-orange-600 dark:text-orange-500 font-bold font-title shrink-0">Cotizar</span>
                    @endif
                  </div>
                  <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed">
                    {{ $service->description }}
                  </p>
                  <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-4 text-xs text-neutral-450 font-medium">
                      @if ($service->duration)
                        <span class="flex items-center gap-1">
                          <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                          Est. {{ $service->duration }} mins
                        </span>
                      @endif
                    </div>
                    
                    <button type="button" wire:click="selectService({{ $service->id }})" class="text-xs font-bold text-orange-600 dark:text-orange-500 hover:underline">
                      Seleccionar &rarr;
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Right side: Interactive Booking Form -->
      <div id="booking-form" class="lg:col-span-5">
        <div class="sticky top-24 bg-white border border-gray-150 rounded-3xl p-8 dark:bg-neutral-850 dark:border-neutral-800 shadow-sm">
          <div class="space-y-2 mb-6 border-b border-gray-150 dark:border-neutral-750 pb-4">
            <h3 class="text-xl font-bold font-title text-gray-900 dark:text-white">Agenda tu Hora</h3>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">Completa tus datos y selecciona el servicio técnico que necesitas cotizar.</p>
          </div>

          <form wire:submit.prevent="submitBooking" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Nombre Completo</label>
              <input type="text" wire:model="name" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="Juan Pérez">
              @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Email</label>
                <input type="email" wire:model="email" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="juan@gmail.com">
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Teléfono</label>
                <input type="text" wire:model="phone" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="+56912345678">
                @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Moto (Marca, Modelo y Año)</label>
              <input type="text" wire:model="motorcycle" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="Bajaj Pulsar NS 200 (2024)">
              @error('motorcycle') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Servicio Técnico a Cotizar</label>
              <select wire:model="service_id" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 py-2.5 focus:border-orange-500 focus:ring-orange-500">
                <option value="">Selecciona un servicio...</option>
                
                @if ($services->isEmpty())
                  <!-- Mock options -->
                  <option value="1">Mantención Preventiva</option>
                  <option value="2">Scanner Electrónico</option>
                  <option value="3">Rescate y Traslado</option>
                @else
                  @foreach ($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                  @endforeach
                @endif
              </select>
              @error('service_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 dark:text-neutral-300 uppercase mb-1">Comentarios / Síntomas de la Moto</label>
              <textarea wire:model="notes" rows="4" class="w-full text-sm rounded-lg border-gray-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 p-2.5 focus:border-orange-500 focus:ring-orange-500" placeholder="Explícanos brevemente qué le ocurre a tu moto o qué necesitas hacerle..."></textarea>
              @error('notes') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full p-3.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm rounded-xl shadow-md transition">
              Agendar Mantención / Cotizar
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
