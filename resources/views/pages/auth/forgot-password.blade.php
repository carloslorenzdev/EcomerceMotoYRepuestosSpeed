<x-layouts::auth :title="'Recuperar Contraseña'">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="'Recuperar Contraseña'" :description="'Ingresa tu correo electrónico para recibir un enlace de restablecimiento'" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="'Correo Electrónico'"
                type="email"
                required
                autofocus
                placeholder="correo@ejemplo.com"
            />

            <flux:button variant="primary" type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white border-transparent" data-test="email-password-reset-link-button">
                Enviar Enlace de Recuperación
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>O regresar al</span>
            <flux:link :href="route('login')" wire:navigate class="font-semibold text-orange-600 dark:text-orange-500">iniciar sesión</flux:link>
        </div>
    </div>
</x-layouts::auth>
