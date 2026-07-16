@section('title', 'Marca y Datos de Contacto')

<div class="space-y-6">
    
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300' }}">
            <span class="font-bold mr-2">Éxito:</span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm max-w-3xl">
        <div class="mb-6">
            <h2 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Datos del Sitio Público</h2>
            <p class="text-[10px] text-gray-400 mt-0.5">Configura la información de contacto y enlaces de redes sociales del storefront.</p>
        </div>

        <form wire:submit.prevent="saveSettings" class="space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Teléfono de Exhibición</label>
                    <input type="text" wire:model="phone_display" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                    @error('phone_display') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Correo de Contacto</label>
                    <input type="email" wire:model="contact_email" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                    @error('contact_email') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Whatsapp -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Número WhatsApp (sólo dígitos, ej: 56950800542)</label>
                    <input type="text" wire:model="whatsapp_digits" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500 font-mono">
                    @error('whatsapp_digits') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Enlace de Instagram</label>
                    <input type="url" wire:model="instagram_url" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                    @error('instagram_url') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dirección Física de la Sucursal</label>
                <input type="text" wire:model="address_line" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500">
                @error('address_line') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Custom Logo URL -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">URL del Logo (Opcional)</label>
                    <input type="text" wire:model="logo_url" placeholder="Dejar vacío para usar logo integrado"
                           class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500 font-mono">
                    @error('logo_url') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Custom Hero Image URL -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">URL Imagen del Hero (Opcional)</label>
                    <input type="text" wire:model="hero_image_url" placeholder="Dejar vacío para usar fondo por defecto"
                           class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500 font-mono">
                    @error('hero_image_url') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-gray-150 dark:border-neutral-800 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-orange-600 px-6 py-3 text-sm font-bold text-white shadow-xs hover:bg-orange-700 transition-colors">
                    Guardar Cambios de Marca
                </button>
            </div>

        </form>
    </div>

</div>
