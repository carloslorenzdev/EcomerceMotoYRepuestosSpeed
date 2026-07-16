<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ filled($title ?? null) ? $title.' - MotosSpeed' : 'MotosSpeed - Tienda de Repuestos y Taller Mecánico' }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        
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
            // Preline UI dark mode helper & Homepage light mode enforcer
            function applyTheme() {
                const html = document.querySelector('html');
                const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
                
                if (isHomePage) {
                    html.classList.remove('dark');
                    html.classList.add('light');
                } else if (localStorage.getItem('hs_theme') === 'dark' || (!('hs_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    html.classList.add('dark');
                    html.classList.remove('light');
                } else {
                    html.classList.remove('dark');
                    html.classList.add('light');
                }
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

        @fluxScripts
    </body>
</html>
