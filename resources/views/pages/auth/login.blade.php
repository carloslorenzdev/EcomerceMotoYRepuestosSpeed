<x-layouts::auth :title="'Iniciar Sesión'">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="'Iniciar sesión en tu cuenta'" :description="'Ingresa tu correo y contraseña a continuación'" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <!-- Passkey verification (If registered) -->
        <x-passkey-verify 
            :label="'Iniciar sesión con llave de paso (Passkey)'"
            :loading-label="'Verificando...'"
            :separator="'O continuar con correo'"
        />

        <!-- Social Google Login button -->
        <div>
            <a href="{{ route('auth.google') }}" class="w-full py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-700 shadow-xs hover:bg-gray-50 hover:text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200 dark:hover:text-white dark:hover:bg-neutral-700 transition-all duration-150">
                <svg class="w-4 h-auto shrink-0" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.114-5.213 4.114-3.535 0-6.402-2.867-6.402-6.402s2.867-6.402 6.402-6.402c1.782 0 3.39.73 4.544 1.91l3.13-3.13C19.347 2.235 15.98 1 12.24 1 6.046 1 1 6.046 1 12.24s5.046 11.24 11.24 11.24c6.046 0 10.963-4.917 10.963-10.963 0-.744-.065-1.463-.18-2.152v-2.08H12.24Z"/>
                </svg>
                Iniciar sesión con Google
            </a>
        </div>

        <!-- Visual Divider -->
        <div class="flex items-center before:flex-grow before:border-t before:border-gray-200 dark:before:border-neutral-750 after:flex-grow after:border-t after:border-gray-200 dark:after:border-neutral-750">
            <span class="mx-3 text-[10px] text-gray-400 dark:text-neutral-500 uppercase font-bold tracking-wider">o ingresar con correo</span>
        </div>

        <!-- Email & Password Form -->
        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="'Correo Electrónico'"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="correo@ejemplo.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="'Contraseña'"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Contraseña"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-xs end-0" :href="route('password.request')" wire:navigate>
                        ¿Olvidaste tu contraseña?
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="'Recordarme en este equipo'" :checked="old('remember')" />

            <div class="flex items-center justify-end mt-2">
                <flux:button variant="primary" type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white border-transparent" data-test="login-button">
                    Iniciar Sesión
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-xs text-center rtl:space-x-reverse text-neutral-500 dark:text-neutral-400">
            <span>¿No tienes una cuenta?</span>
            <flux:link :href="route('register')" wire:navigate class="font-semibold text-orange-600 dark:text-orange-500">Regístrate</flux:link>
        </div>
    </div>
</x-layouts::auth>
