@php
  $settings = \App\Models\SiteSetting::first() ?? new \App\Models\SiteSetting();
  $address = $settings->address_line ?: "Av. Illanes 305-A, Rancagua, O'Higgins";
  $phoneDisplay = $settings->phone_display ?: "+56 9 5080 0542";
  $email = $settings->contact_email ?: "contacto@motosyrepuestosspeed.cl";
  $whatsapp = $settings->whatsapp_digits ?: "56950800542";
@endphp
<footer class="w-full bg-neutral-950 text-neutral-400 mt-auto border-t border-neutral-900 relative z-10">
  <div class="max-w-[85rem] py-8 px-4 sm:px-6 lg:px-8 mx-auto">
    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
      
      <!-- Brand & Description -->
      <div class="flex flex-col gap-4">
        <a class="flex items-center" href="{{ route('home') }}">
          <x-store.logo class="h-10 w-auto" textColor="#ffffff" />
        </a>
        <p class="text-xs text-neutral-400 leading-relaxed">
          Tu tienda de confianza para motos, repuestos y accesorios. Calidad garantizada y los mejores precios.
        </p>
        
        <!-- Social & Payment Badges -->
        <div class="mt-2 rounded-xl border border-neutral-800 bg-neutral-900/50 p-3 w-fit">
          <p class="text-[9px] font-bold uppercase tracking-widest text-neutral-500 mb-2">Pagos Seguros</p>
          <div class="flex items-center gap-2">
            <div class="flex h-6 items-center justify-center rounded-md bg-white px-1.5 shadow-sm">
              <img src="https://logospng.org/download/mercado-pago/logo-mercado-pago-1024.png" alt="Mercado Pago" class="h-3 w-auto object-contain" />
            </div>
            <span class="text-[11px] font-medium text-neutral-400">Débito · Crédito</span>
          </div>
        </div>
      </div>

      <!-- Quick links -->
      <div>
        <p class="text-sm font-bold uppercase tracking-[0.15em] text-white border-l-2 border-orange-500 pl-3 mb-4">Navegación</p>
        <ul class="space-y-2.5 text-sm text-neutral-400">
          <li>
            <a class="hover:text-white transition-colors inline-flex items-center gap-2 group" href="{{ route('home') }}" wire:navigate>
              <span class="w-1.5 h-1.5 rounded-full bg-neutral-700 group-hover:bg-orange-500 transition-colors"></span> Inicio
            </a>
          </li>
          <li>
            <a class="hover:text-white transition-colors inline-flex items-center gap-2 group" href="{{ route('shop') }}" wire:navigate>
              <span class="w-1.5 h-1.5 rounded-full bg-neutral-700 group-hover:bg-orange-500 transition-colors"></span> Tienda
            </a>
          </li>
          <li>
            <a class="hover:text-white transition-colors inline-flex items-center gap-2 group" href="{{ route('services') }}" wire:navigate>
              <span class="w-1.5 h-1.5 rounded-full bg-neutral-700 group-hover:bg-orange-500 transition-colors"></span> Servicios
            </a>
          </li>
          <li>
            <a class="hover:text-white transition-colors inline-flex items-center gap-2 group" href="{{ route('login') }}" wire:navigate>
              <span class="w-1.5 h-1.5 rounded-full bg-neutral-700 group-hover:bg-orange-500 transition-colors"></span> Mi Cuenta
            </a>
          </li>
        </ul>
      </div>

      <!-- Contact details -->
      <div>
        <p class="text-sm font-bold uppercase tracking-[0.15em] text-white border-l-2 border-orange-500 pl-3 mb-4">Contacto</p>
        <ul class="space-y-3.5 text-sm text-neutral-400">
          <li class="flex gap-3">
            <svg class="size-4 shrink-0 text-orange-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="leading-tight">{{ $address }}</span>
          </li>
          <li class="flex gap-3 items-center">
            <svg class="size-4 shrink-0 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <a class="hover:text-white transition-colors" href="tel:+{{ preg_replace('/[^0-9]/', '', $phoneDisplay) }}">{{ $phoneDisplay }}</a>
          </li>
          <li class="flex gap-3 items-center">
            <svg class="size-4 shrink-0 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <a class="hover:text-white transition-colors text-xs" href="mailto:{{ $email }}">{{ $email }}</a>
          </li>
        </ul>
      </div>

      <!-- Map Location -->
      <div class="flex flex-col h-full">
        <p class="text-sm font-bold uppercase tracking-[0.15em] text-white border-l-2 border-orange-500 pl-3 mb-4">Ubicación</p>
        <div class="flex-1 min-h-[140px] rounded-xl overflow-hidden border border-neutral-800 bg-neutral-900/50 relative group">
          <iframe 
            title="Ubicación"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3314.150772719574!2d-70.7420556234914!3d-34.16611083431698!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x96634354c2ea2387%3A0xc3457a15ec92ff81!2sAv.%20Illanes%20305-A%2C%20Rancagua%2C%20O&#39;Higgins!5e0!3m2!1ses-419!2scl!4v1716301328906!5m2!1ses-419!2scl" 
            class="absolute inset-0 w-full h-full border-0 grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>

    </div>
    <!-- End Grid -->

    <!-- Bottom details -->
    <div class="pt-6 border-t border-neutral-800 flex flex-col sm:flex-row items-center justify-between text-xs text-neutral-500 gap-4">
      <p>© {{ date('Y') }} MotosSpeed. Todos los derechos reservados.</p>
      
      <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
        <p class="inline-flex items-center justify-center gap-1.5">
          Hecho con
          <svg class="h-3.5 w-3.5 text-red-500" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
          </svg>
          por
        </p>
        
        <!-- CrlDev Official Badge -->
        <a 
          href="https://carloslorenzdev.netlify.app/" 
          target="_blank" 
          rel="noopener noreferrer" 
          class="flex items-center gap-1.5 opacity-90 hover:opacity-100 transition-all bg-white py-1 px-2 rounded shadow-sm hover:scale-105"
        >
          <div class="text-[#0f172a] font-black text-lg leading-none tracking-tighter" style="margin-top: -1px;">
            &lt;/&gt;
          </div>
          <div class="flex flex-col text-[#0f172a]">
            <span class="text-sm font-black tracking-tight leading-none font-title">CrlDev</span>
            <span class="text-[5px] font-bold tracking-[0.2em] mt-0.5">SOFTWARE DEV</span>
          </div>
        </a>
      </div>
    </div>

  </div>
</footer>
