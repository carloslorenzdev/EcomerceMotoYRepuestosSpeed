<header x-data="{ isScrolled: false, isMobileMenuOpen: false }"
        x-init="isScrolled = window.pageYOffset > 20"
        @scroll.window="isScrolled = window.pageYOffset > 20"
        class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-500 py-0 md:py-3 pointer-events-none">
  
  <nav :class="isMobileMenuOpen 
            ? 'w-full bg-[#0B0F19]/95 border-b border-neutral-800/80 px-4 py-3 text-white' 
            : (isScrolled 
                ? 'w-full md:max-w-[50rem] bg-[#070b13]/90 border-b md:border border-neutral-800/80 md:border-neutral-850 shadow-2xl md:rounded-full px-4 py-3 md:px-6 md:py-2 mx-auto text-white backdrop-blur-md' 
                : 'w-full max-w-[85rem] bg-transparent border-transparent px-4 py-3 mx-auto text-gray-800 dark:text-neutral-200')"
       class="w-full flex flex-col md:flex-row items-center justify-between transition-all duration-500 pointer-events-auto"
       aria-label="Global">
    
    <!-- Left Section: Logo & Mobile Toggle -->
    <div class="w-full md:w-auto flex items-center justify-between py-2 md:py-0 flex-none">
      <a class="flex items-center justify-center transition-all duration-500 shrink-0" 
         :class="isScrolled ? 'h-11 w-14' : 'h-18 w-24'" 
         href="{{ route('home') }}" wire:navigate>
        <x-store.logo class="w-full h-full drop-shadow-md" textColor="currentColor" />
      </a>

      <div class="flex items-center gap-2 md:hidden">
        <!-- Cart Icon (Mobile) -->
        <button type="button" @click="Livewire.dispatch('openCart')" 
                class="relative p-2 rounded-full hover:bg-white/10 transition-colors"
                :class="isScrolled || isMobileMenuOpen ? 'text-white' : 'text-gray-600 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500'">
          <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
          <livewire:store.cart-count />
        </button>

        <!-- Toggle Mobile Menu Button -->
        <button type="button" @click="isMobileMenuOpen = !isMobileMenuOpen"
                class="flex justify-center items-center size-9 text-sm font-semibold rounded-full border transition-all duration-300"
                :class="isScrolled || isMobileMenuOpen 
                    ? 'border-neutral-800 text-white hover:bg-white/10' 
                    : 'border-gray-200 text-gray-800 hover:bg-gray-100 dark:text-white dark:border-neutral-700 dark:hover:bg-neutral-700'"
                aria-label="Toggle navigation">
          <svg class="size-4 transition-transform duration-300" :class="isMobileMenuOpen ? 'rotate-90 hidden' : 'block'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg class="size-4 transition-transform duration-300" :class="isMobileMenuOpen ? 'rotate-90 block' : 'hidden'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Center/Right Sections (Collapsible Wrapper) -->
    <div :class="isMobileMenuOpen ? 'block' : 'hidden'" 
         class="overflow-hidden transition-all duration-300 basis-full grow md:block w-full">
      <div class="flex flex-col md:flex-row items-center w-full mt-4 md:mt-0">
        
        <!-- Middle Section: Center Links -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-y-4 gap-x-8 w-full md:grow">
          <a class="text-sm font-semibold transition-colors duration-300 py-1"
             :class="isScrolled || isMobileMenuOpen 
                ? '{{ request()->routeIs('home') ? 'text-orange-500 font-bold' : 'text-neutral-350 hover:text-white' }}' 
                : '{{ request()->routeIs('home') ? 'text-orange-600 dark:text-orange-500 font-bold' : 'text-gray-600 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500' }}'"
             href="{{ route('home') }}" wire:navigate>Inicio</a>
             
          <a class="text-sm font-semibold transition-colors duration-300 py-1"
             :class="isScrolled || isMobileMenuOpen 
                ? '{{ request()->routeIs('shop') ? 'text-orange-500 font-bold' : 'text-neutral-350 hover:text-white' }}' 
                : '{{ request()->routeIs('shop') ? 'text-orange-600 dark:text-orange-500 font-bold' : 'text-gray-600 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500' }}'"
             href="{{ route('shop') }}" wire:navigate>Tienda</a>
             
          <a class="text-sm font-semibold transition-colors duration-300 py-1"
             :class="isScrolled || isMobileMenuOpen 
                ? '{{ request()->routeIs('services') ? 'text-orange-500 font-bold' : 'text-neutral-350 hover:text-white' }}' 
                : '{{ request()->routeIs('services') ? 'text-orange-600 dark:text-orange-500 font-bold' : 'text-gray-600 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500' }}'"
             href="{{ route('services') }}" wire:navigate>Servicios</a>
        </div>

        <!-- Right Section: Actions -->
        <div class="flex items-center justify-center gap-x-4 py-4 md:py-0 border-t border-gray-200/10 md:border-t-0 w-full md:w-auto flex-none mt-4 md:mt-0">
          
          @if(!request()->routeIs('home'))
          <!-- Theme Toggle -->
          <button type="button" class="hs-dark-mode-active:hidden block hs-dark-mode p-2 rounded-full hover:bg-white/10 transition-colors"
                  :class="isScrolled || isMobileMenuOpen ? 'text-neutral-300' : 'text-gray-600 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500'" 
                  data-hs-theme-click-value="dark">
            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
          </button>
          <button type="button" class="hs-dark-mode-active:block hidden hs-dark-mode p-2 rounded-full hover:bg-white/10 transition-colors"
                  :class="isScrolled || isMobileMenuOpen ? 'text-neutral-300' : 'text-gray-600 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500'" 
                  data-hs-theme-click-value="light">
            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
          </button>
          @endif

          <!-- Cart Icon (Desktop) -->
          <button type="button" @click="Livewire.dispatch('openCart')" 
                  class="hidden md:block relative p-2 rounded-full hover:bg-white/10 transition-colors"
                  :class="isScrolled || isMobileMenuOpen ? 'text-neutral-300' : 'text-gray-600 hover:text-orange-600 dark:text-neutral-400 dark:hover:text-orange-500'">
            <svg class="size-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <livewire:store.cart-count />
          </button>

          <!-- User Session actions -->
          @auth
            <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
              <button id="hs-dropdown-avatar" type="button" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:text-white">
                <span class="flex items-center justify-center size-8 bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-200 font-bold rounded-full border border-orange-200 dark:border-orange-900">
                  {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
              </button>
              <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-750" aria-labelledby="hs-dropdown-avatar">
                <div class="py-3 px-5 -m-2 bg-gray-50 rounded-t-lg dark:bg-neutral-800/50">
                  <p class="text-sm text-gray-500 dark:text-neutral-400">Sesión iniciada como</p>
                  <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ Auth::user()->name }}</p>
                </div>
                <div class="mt-2 py-1.5 border-t border-gray-100 dark:border-neutral-700">
                  @if (Auth::user()->isAdmin())
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="{{ route('admin.dashboard') }}">
                      Administración
                    </a>
                  @else
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="{{ route('dashboard') }}">
                      Panel Cliente
                    </a>
                  @endif
                  <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="{{ route('profile.edit') }}">
                    Mi Cuenta
                  </a>
                  
                  <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/20">
                      Cerrar Sesión
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @else
            <a href="{{ route('login') }}" 
               class="inline-flex items-center gap-x-2 font-bold rounded-full border border-transparent bg-orange-600 text-white hover:bg-orange-700 shadow-sm transition-all duration-300"
               :class="isScrolled ? 'py-1.5 px-4 text-xs' : 'py-2 px-5 text-sm'"
               wire:navigate>
              Iniciar Sesión
            </a>
          @endauth

        </div>
      </div>
    </div>
  </nav>
</header>
