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
    <body class="min-h-screen bg-gray-50 dark:bg-neutral-900 text-gray-800 dark:text-neutral-200 antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            
            <!-- Left Branding Panel (Always dark & premium) -->
            <div class="bg-neutral-950 relative hidden h-full flex-col p-10 text-white lg:flex border-e border-neutral-200/10 dark:border-neutral-800">
                <!-- Background image and overlay -->
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-25" style="background-image: url('/bajaj-ns200.jpg');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/70 to-neutral-950/30"></div>
                
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium gap-2" wire:navigate>
                    <x-store.logo class="h-10 w-auto shrink-0" textColor="#ffffff" />
                    <span class="font-black text-xl tracking-wider text-white">MotosSpeed</span>
                </a>

                @php
                    $quotes = [
                        ["La libertad no se explica, se siente en cada aceleración.", "MotosSpeed"],
                        ["Dos ruedas mueven el alma, cuatro mueven el cuerpo.", "Pasión Motera"],
                        ["El camino es el destino, vívelo sobre dos ruedas.", "Ruta Libre"],
                        ["El viento en la cara es la mejor terapia para el alma.", "Espíritu Libre"],
                        ["No es solo un viaje, es la emoción de cada curva.", "MotosSpeed"]
                    ];
                    $quote = $quotes[array_rand($quotes)];
                    $message = $quote[0];
                    $author = $quote[1];
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2 border-l-4 border-orange-500 pl-4">
                        <flux:heading size="lg" class="text-slate-200">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading class="text-orange-500 font-bold">— {{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            
            <!-- Right Content Panel (Responsive light/dark) -->
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[360px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <x-store.logo class="h-14 w-auto shrink-0 drop-shadow-md" textColor="currentColor" />
                        <span class="text-xl font-black text-gray-900 dark:text-white tracking-wider">MotosSpeed</span>
                    </a>
                    
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @livewireScripts
        @fluxScripts
    </body>
</html>
