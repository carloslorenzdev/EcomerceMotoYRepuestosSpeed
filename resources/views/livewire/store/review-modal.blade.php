<div>
  @if ($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#070b13]/60 backdrop-blur-xs">
      <!-- Modal card -->
      <div class="relative w-full max-w-md bg-white dark:bg-neutral-900 rounded-3xl p-6 shadow-2xl border border-gray-150 dark:border-neutral-800 text-center transition-all animate-fade-in duration-300">
        
        <!-- Close button -->
        <button type="button" wire:click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-white">
          <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        @if (!$submitted)
          <!-- Header -->
          <div class="mb-5 mt-2">
            <div class="inline-flex items-center justify-center size-14 bg-orange-100 text-orange-650 dark:bg-orange-950/40 dark:text-orange-500 rounded-full mb-3 shadow-inner">
              <svg class="size-7" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white font-title">¡Gracias por tu compra!</h3>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">¿Qué tal estuvo tu experiencia en Motos Speed?</p>
          </div>

          <!-- Star Rating Selector -->
          <div class="flex justify-center gap-2 mb-6" x-data="{ tempRating: @entangle('rating') }">
            @for ($i = 1; $i <= 5; $i++)
              <button type="button" @click="tempRating = {{ $i }}" 
                      class="transition-transform active:scale-95 duration-100 focus:outline-hidden">
                <svg class="size-9 transition-colors duration-150" 
                     :class="tempRating >= {{ $i }} ? 'text-orange-500 fill-orange-500' : 'text-gray-350 dark:text-neutral-700'"
                     fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </button>
            @endfor
          </div>

          <!-- Form inputs -->
          <form wire:submit.prevent="submitReview" class="space-y-4 text-left">
            <div>
              <label class="block text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1.5">Tu Nombre</label>
              <input type="text" wire:model="customer_name" required
                     class="w-full text-xs rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500 p-3">
            </div>

            <div>
              <label class="block text-[10px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1.5">Comentarios (Opcional)</label>
              <textarea wire:model="comment" rows="3" placeholder="Ej: Excelente servicio, repuestos 100% originales..."
                        class="w-full text-xs rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-orange-500 p-3"></textarea>
            </div>

            <button type="submit" class="w-full justify-center inline-flex items-center rounded-xl bg-orange-600 px-4 py-3 text-xs font-bold text-white shadow-xs hover:bg-orange-700 transition-colors mt-2">
              Enviar Opinión
            </button>
          </form>
        @else
          <!-- Success State -->
          <div class="py-6 space-y-4">
            <div class="inline-flex items-center justify-center size-16 bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-500 rounded-full shadow-inner">
              <svg class="size-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
              </svg>
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white font-title">¡Opinión enviada!</h3>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 max-w-xs mx-auto">Tus comentarios nos ayudan a mejorar el taller de Motos Speed día a día.</p>
            <div class="pt-4">
              <button type="button" wire:click="closeModal" class="px-6 py-2.5 rounded-xl bg-neutral-900 hover:bg-neutral-850 text-white dark:bg-neutral-800 dark:hover:bg-neutral-700 text-xs font-bold transition-colors">
                Cerrar
              </button>
            </div>
          </div>
        @endif

      </div>
    </div>
  @endif
</div>
