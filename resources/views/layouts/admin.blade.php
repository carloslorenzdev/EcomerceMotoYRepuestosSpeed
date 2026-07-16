<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <script>
            // Apply theme preference on load
            const html = document.querySelector('html');
            if (localStorage.getItem('hs_theme') === 'dark' || (!('hs_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        </script>
    </head>
    <body x-data="{ mobileMenuOpen: false }" 
          class="min-h-screen bg-gray-50 text-gray-800 dark:bg-neutral-950 dark:text-neutral-200 antialiased transition-colors duration-300">
        
        <!-- Mobile Top Header Bar -->
        <div class="flex items-center justify-between border-b border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 px-4 py-3 lg:hidden">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <x-store.logo class="h-8 w-auto shrink-0" textColor="currentColor" />
                <span class="text-xs font-black tracking-widest uppercase text-gray-900 dark:text-white">Admin</span>
            </a>
            <button type="button" @click="mobileMenuOpen = true"
                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white"
                    aria-label="Abrir menú">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Backdrop Overlay for Mobile Sidebar Drawer -->
        <div class="fixed inset-0 z-40 bg-neutral-950/60 backdrop-blur-xs transition-opacity duration-300 lg:hidden"
             :class="mobileMenuOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
             @click="mobileMenuOpen = false"></div>

        <div class="lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
            
            <!-- Left Sidebar Navigation -->
            <aside class="fixed inset-y-0 left-0 z-50 flex w-[18rem] flex-col bg-neutral-950 border-r border-neutral-900 text-white transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
                   :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'">
                
                <!-- Sidebar Header: Brand Logo -->
                <div class="flex items-center justify-between border-b border-neutral-900 px-6 py-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 w-full" @click="mobileMenuOpen = false">
                        <x-store.logo class="h-12 w-auto drop-shadow-md" textColor="#ffffff" />
                        <p class="text-[10px] text-orange-500 font-extrabold uppercase tracking-widest mt-2">MotosSpeed Panel</p>
                    </a>
                    <button type="button" @click="mobileMenuOpen = false"
                            class="rounded-lg p-1.5 text-neutral-400 hover:bg-white/5 hover:text-white lg:hidden"
                            aria-label="Cerrar menú">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar Body: Links -->
                <nav class="flex-1 space-y-1 px-4 py-4 overflow-y-auto">
                    
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'text-neutral-450 hover:bg-white/5 hover:text-white' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                        </svg>
                        Panel General
                    </a>

                    <!-- Products -->
                    <a href="{{ route('admin.products') }}" 
                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.products') ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'text-neutral-450 hover:bg-white/5 hover:text-white' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Productos
                    </a>

                    <!-- Categories -->
                    <a href="{{ route('admin.categories') }}" 
                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.categories') ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'text-neutral-450 hover:bg-white/5 hover:text-white' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Categorías
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('admin.orders') }}" 
                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.orders') ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'text-neutral-455 hover:bg-white/5 hover:text-white' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Pedidos y Envíos
                    </a>

                    <!-- Inventory -->
                    <a href="{{ route('admin.inventory') }}" 
                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.inventory') ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'text-neutral-455 hover:bg-white/5 hover:text-white' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Inventario
                    </a>

                    <!-- System -->
                    <a href="{{ route('admin.system') }}" 
                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.system') ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'text-neutral-455 hover:bg-white/5 hover:text-white' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Sistema y Logs
                    </a>

                    <!-- Profile Settings -->
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('profile.edit') || request()->routeIs('security.edit') || request()->routeIs('appearance.edit') ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'text-neutral-455 hover:bg-white/5 hover:text-white' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mi Perfil
                    </a>

                    <!-- Return to Shop -->
                    <div class="pt-4 border-t border-neutral-900 mt-4">
                        <a href="{{ route('home') }}" 
                           class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold text-neutral-450 hover:bg-white/5 hover:text-white transition-all duration-200">
                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Ver Tienda Pública
                        </a>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div class="border-t border-neutral-900 p-5 text-xs text-neutral-500">
                    <p class="font-bold uppercase tracking-wider text-neutral-600 mb-1">MotosSpeed Admin</p>
                    <p class="leading-relaxed">Panel de control del e-commerce y sincronización de RelBase.</p>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex min-h-screen flex-col overflow-x-hidden">
                
                <!-- Main Header Navbar -->
                <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/90 dark:border-neutral-800 dark:bg-neutral-900/90 px-6 py-3.5 backdrop-blur-md transition-colors duration-300">
                    <div class="flex items-center justify-between gap-4">
                        
                        <!-- Search Mockup & Page Title -->
                        <div class="flex items-center gap-4 flex-1">
                            <div class="hidden sm:block">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-500">Administración</p>
                                <h1 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">@yield('title', 'Gestiona tu e-commerce')</h1>
                            </div>
                            
                            <!-- Search bar header -->
                            <div class="relative w-full max-w-xs hidden md:block">
                                <input type="text" placeholder="Buscar..." 
                                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-gray-200 bg-gray-50/50 text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-1 focus:ring-orange-500 transition-all">
                                <svg class="absolute left-3 top-2.5 size-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Header Controls -->
                        <div class="flex items-center gap-4">
                            
                            <!-- Theme Switcher -->
                            <button type="button" class="hs-dark-mode-active:hidden block p-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400 transition-colors" data-hs-theme-click-value="dark">
                                <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </button>
                            <button type="button" class="hs-dark-mode-active:block hidden p-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400 transition-colors" data-hs-theme-click-value="light">
                                <svg class="size-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                </svg>
                            </button>

                            <!-- Profile Circular Dropdown menu -->
                            <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                                <button id="hs-dropdown-profile" type="button" 
                                        class="inline-flex items-center justify-center size-9 rounded-full bg-orange-600 hover:bg-orange-700 hover:scale-105 active:scale-95 text-white font-bold transition-all shadow-md focus:outline-hidden">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                </button>
                                
                                <div class="hs-dropdown-menu transition-[opacity,margin] duration-150 hs-dropdown-open:opacity-100 opacity-0 hidden min-w-56 bg-white shadow-xl rounded-xl p-2 mt-2 border border-gray-250 dark:bg-neutral-900 dark:border-neutral-800" aria-labelledby="hs-dropdown-profile">
                                    <div class="py-2.5 px-4 -m-2 bg-gray-50 dark:bg-neutral-800/50 rounded-t-xl">
                                        <p class="text-[10px] text-neutral-500">Sesión iniciada como</p>
                                        <p class="text-xs font-semibold text-gray-800 dark:text-neutral-200 truncate">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                                    </div>
                                    <div class="mt-2 py-1 border-t border-gray-150 dark:border-neutral-800 space-y-0.5">
                                        <a href="{{ route('profile.edit') }}" class="flex w-full items-center py-2 px-3 rounded-lg text-xs text-gray-700 hover:bg-gray-50 dark:text-neutral-350 dark:hover:bg-neutral-800">
                                            Configurar Perfil
                                        </a>
                                        <a href="{{ route('security.edit') }}" class="flex w-full items-center py-2 px-3 rounded-lg text-xs text-gray-700 hover:bg-gray-50 dark:text-neutral-350 dark:hover:bg-neutral-800">
                                            Seguridad y Llaves
                                        </a>
                                        <a href="{{ route('appearance.edit') }}" class="flex w-full items-center py-2 px-3 rounded-lg text-xs text-gray-700 hover:bg-gray-50 dark:text-neutral-350 dark:hover:bg-neutral-800">
                                            Apariencia
                                        </a>
                                        <div class="border-t border-gray-100 dark:border-neutral-800 mt-1 pt-1">
                                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                                @csrf
                                                <button type="submit" class="flex w-full items-center py-2 px-3 rounded-lg text-xs text-red-650 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/20 font-bold">
                                                    Cerrar Sesión
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </header>

                <!-- Page Content Slot -->
                <main class="flex-1 p-6 md:p-8 max-w-7.5xl w-full mx-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
