<x-layouts::auth :title="'Verificación de Correo'">
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center">
            Por favor, verifica tu dirección de correo haciendo clic en el enlace que te acabamos de enviar por correo.
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste al registrarte.
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full bg-orange-600 hover:bg-orange-700 text-white border-transparent">
                    Reenviar Correo de Verificación
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:button variant="ghost" type="submit" class="w-full text-sm cursor-pointer" data-test="logout-button">
                    Cerrar Sesión
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
