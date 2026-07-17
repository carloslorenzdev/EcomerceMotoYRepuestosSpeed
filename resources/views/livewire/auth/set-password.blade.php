<div class="flex flex-col gap-6">
    <x-auth-header
        :title="'Crea tu Contraseña'"
        :description="'Por seguridad, por favor define una contraseña para proteger tu cuenta antes de continuar.'"
    />

    <div class="bg-gray-100 dark:bg-neutral-800 rounded-lg p-3 text-center border border-gray-200 dark:border-neutral-700">
        <span class="text-sm text-gray-600 dark:text-neutral-400">Asegurando cuenta:</span><br>
        <strong class="text-gray-900 dark:text-white">{{ auth()->user()->email }}</strong>
    </div>

    <form wire:submit.prevent="save" class="flex flex-col gap-6">
        <!-- New Password -->
        <div>
            <flux:input
                wire:model="password"
                name="password"
                :label="'Nueva Contraseña'"
                type="password"
                required
                placeholder="Mínimo 8 caracteres"
                viewable
            />
            @error('password')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <flux:input
                wire:model="password_confirmation"
                name="password_confirmation"
                :label="'Confirmar Contraseña'"
                type="password"
                required
                placeholder="Repite la contraseña"
                viewable
            />
            @error('password_confirmation')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <flux:button variant="primary" type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white border-transparent">
            Establecer Contraseña
        </flux:button>
    </form>
</div>
