<x-layouts::auth :title="'Restablecer Contraseña'">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="'Restablecer Contraseña'" :description="'Por favor ingresa tu nueva contraseña a continuación'" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="'Correo Electrónico'"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="'Nueva Contraseña'"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Nueva Contraseña"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="'Confirmar Nueva Contraseña'"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Confirmar Nueva Contraseña"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full bg-orange-600 hover:bg-orange-700 text-white border-transparent" data-test="reset-password-button">
                    Restablecer Contraseña
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
