<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ filled($title ?? null) ? $title.' - MotosSpeed' : 'MotosSpeed - Tienda de Repuestos y Taller Mecánico' }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}?v=2" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Google Fonts (Outfit & Inter) for Premium look -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <style>
            body {
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            }
            .font-title {
                font-family: 'Outfit', sans-serif;
            }
        </style>
        
        <script>
            // Force light mode everywhere on the storefront
            function applyTheme() {
                const html = document.querySelector('html');
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('hs_theme', 'light');
            }

            // Run initially
            applyTheme();

            // Run on Livewire SPA navigation
            document.addEventListener('livewire:navigated', applyTheme);
        </script>
    </head>
    <body class="bg-gray-50 text-gray-800 dark:bg-neutral-900 dark:text-neutral-200 antialiased min-h-screen flex flex-col">
        
        <!-- Navbar / Header -->
        <x-store.header />

        <!-- Main Content -->
        <main class="flex-grow {{ request()->routeIs('home') ? '' : 'pt-24 md:pt-28' }}">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-store.footer />

        <!-- Shopping Cart Drawer (Slide-over component) -->
        <livewire:store.cart />

        <!-- Review / Opinion Pop-up Modal -->
        <livewire:store.review-modal />

        <!-- Toast Notifications group -->
        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        <!-- Floating WhatsApp Button -->
        @php
            $wSettings = \App\Models\SiteSetting::first();
            $waNumber = $wSettings->whatsapp_digits ?? '56950800542';
        @endphp
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noreferrer" class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg shadow-[#25D366]/40 transition-all hover:scale-110 hover:bg-[#1DA851] focus:outline-hidden animate-softpulse" aria-label="Escribir por WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16">
              <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
            </svg>
        </a>

        @fluxScripts
    </body>
</html>
