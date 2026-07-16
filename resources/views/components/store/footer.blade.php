<footer class="w-full bg-neutral-900 text-neutral-400 mt-auto">
  <div class="max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
      
      <!-- Brand & Description -->
      <div class="col-span-1 md:col-span-2">
        <a class="flex items-center gap-2 text-2xl font-bold font-title text-orange-500 mb-4" href="{{ route('home') }}">
          <span class="p-1 bg-orange-500 text-white rounded">
            <svg class="size-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 7c0-1.1-.9-2-2-2h-3v2h3v2.68l-3.07 3.07-2.61-2.61c-.39-.39-1.02-.39-1.41 0L9.41 12H5c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h2c0 1.66 1.34 3 3 3s3-1.34 3-3h2c0 1.66 1.34 3 3 3s3-1.34 3-3h1c1.1 0 2-.9 2-2v-5l-4-4V7zm-9 11c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm8 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-4.32-6L17 8.68V11h-3.32z"/>
            </svg>
          </span>
          MotosSpeed
        </a>
        <p class="text-sm text-neutral-400 max-w-sm mb-4 leading-relaxed">
          Tu taller mecánico de confianza y distribuidor de repuestos originales en Rancagua. Especialistas en motos Bajaj, mantenciones preventivas y rescates en ruta.
        </p>
        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-semibold bg-orange-500/10 text-orange-400 border border-orange-500/20">
          Distribuidor Oficial Bajaj
        </span>
      </div>

      <!-- Quick links -->
      <div>
        <h4 class="font-bold text-white uppercase tracking-wider text-sm mb-4">Navegación</h4>
        <ul class="space-y-2 text-sm">
          <li><a class="hover:text-orange-500 transition-colors" href="{{ route('home') }}" wire:navigate>Inicio</a></li>
          <li><a class="hover:text-orange-500 transition-colors" href="{{ route('shop') }}" wire:navigate>Tienda de Repuestos</a></li>
          <li><a class="hover:text-orange-500 transition-colors" href="{{ route('services') }}" wire:navigate>Nuestros Servicios</a></li>
          <li><a class="hover:text-orange-500 transition-colors" href="{{ route('login') }}" wire:navigate>Área de Clientes</a></li>
        </ul>
      </div>

      <!-- Contact details -->
      <div>
        <h4 class="font-bold text-white uppercase tracking-wider text-sm mb-4">Contacto y Horarios</h4>
        <ul class="space-y-3 text-sm text-neutral-400">
          <li class="flex gap-2">
            <svg class="size-5 shrink-0 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Av. Illanes 305-A, Rancagua, Chile</span>
          </li>
          <li class="flex gap-2">
            <svg class="size-5 shrink-0 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <a class="hover:text-orange-500 transition-colors" href="tel:+56950800542">+56 9 5080 0542</a>
          </li>
          <li class="flex gap-2">
            <svg class="size-5 shrink-0 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <a class="hover:text-orange-500 transition-colors text-xs" href="mailto:contacto@motosyrepuestosspeed.cl">contacto@motosyrepuestosspeed.cl</a>
          </li>
          <li class="pt-2 border-t border-neutral-800 text-xs">
            Lun - Vie: 09:00 - 18:30<br>
            Sáb: 09:30 - 14:00
          </li>
        </ul>
      </div>

    </div>
    <!-- End Grid -->

    <!-- Bottom details -->
    <div class="pt-5 border-t border-neutral-800 flex flex-col sm:flex-row items-center justify-between text-xs text-neutral-500 gap-4">
      <p>© {{ date('Y') }} MotosSpeed. Todos los derechos reservados.</p>
      
      <!-- Payments systems logos indicators -->
      <div class="flex items-center gap-3">
        <span class="text-[10px] uppercase font-semibold text-neutral-600">Medios de Pago:</span>
        <!-- Mercado Pago badge -->
        <span class="px-2 py-1 bg-white/5 border border-white/10 rounded font-semibold text-neutral-300">Mercado Pago</span>
        <!-- Webpay / Redcompra placeholder -->
        <span class="px-2 py-1 bg-white/5 border border-white/10 rounded font-semibold text-neutral-300">Webpay</span>
      </div>
    </div>

  </div>
</footer>
