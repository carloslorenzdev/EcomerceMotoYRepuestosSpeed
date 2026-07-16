<x-layouts::auth :title="'Confirmar Contraseña'">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="'Confirmar Contraseña'"
            :description="'Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.'"
        />

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
