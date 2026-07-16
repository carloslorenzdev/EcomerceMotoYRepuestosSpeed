<x-layouts::auth :title="'Confirmar Contraseña'">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="'Confirmar Contraseña'"
            :description="'Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.'"
        />

        @if(auth()->user() && auth()->user()->google_id)
            <div class="p-4 rounded-xl bg-orange-500/10 border border-orange-500/20 text-xs text-orange-650 dark:text-orange-400">
                <p class="font-bold mb-1">¿Iniciaste sesión con Google?</p>
                <p class="leading-relaxed">Como te registraste con tu cuenta de Google, no tienes una contraseña establecida. Si deseas ingresar a estas secciones o crear una contraseña propia, puedes <a href="{{ route('password.request') }}" class="underline font-bold text-orange-600 dark:text-orange-500 hover:text-orange-700 dark:hover:text-orange-400" wire:navigate>crear o restablecer tu contraseña aquí</a> para recibir un enlace en tu correo.</p>
            </div>
        @endif

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify
            options-route="passkey.confirm-options"
            submit-route="passkey.confirm"
            :label="'Confirmar con llave de paso (Passkey)'"
            :loading-label="'Confirmando...'"
            :separator="'O confirmar con contraseña'"
        />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="'Contraseña'"
                type="password"
                required
                autocomplete="current-password"
                placeholder="Contraseña"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white border-transparent" data-test="confirm-password-button">
                Confirmar Contraseña
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
