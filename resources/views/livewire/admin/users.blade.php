@section('title', 'Registro de Usuarios')

<div class="space-y-6">
    
    <!-- Toast notifications check -->
    @if (session()->has('toast'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-5 right-5 z-50 flex items-center p-4 rounded-xl shadow-lg border text-sm
             {{ session('toast')['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300' : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-900 dark:text-red-300' }}">
            <span class="font-bold mr-2">
                {{ session('toast')['type'] === 'success' ? 'Éxito:' : 'Error:' }}
            </span>
            {{ session('toast')['message'] }}
        </div>
    @endif

    <!-- Toolbar -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="relative w-full sm:max-w-xs">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar por nombre o correo..."
                       class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 bg-white text-gray-800 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-orange-500">
                <svg class="absolute left-3 top-2.5 size-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

        </div>
    </div>

    <!-- Users Table Card -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-800 text-left text-xs">
                <thead class="bg-gray-50 dark:bg-neutral-850 text-gray-500 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Usuario</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Correo Electrónico</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Estado</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Rol del Sistema</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($users as $user)
                        <tr class="align-middle {{ $user->status === 'suspendido' ? 'opacity-60' : '' }}">
                            <!-- Name -->
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center justify-center size-8 bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-200 font-bold rounded-lg shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-4 py-4 text-gray-700 dark:text-neutral-350">
                                {{ $user->email }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $user->status === 'activo' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300' }}">
                                    {{ $user->status }}
                                </span>
                            </td>

                            <!-- Role selection -->
                            <td class="px-4 py-4">
                                <div class="flex justify-center">
                                    <select wire:change="changeRole({{ $user->id }}, $event.target.value)"
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                            class="px-2.5 py-1.5 text-xs rounded-lg border border-gray-200 bg-white text-gray-800 disabled:opacity-60 dark:border-neutral-750 dark:bg-neutral-800 dark:text-white focus:outline-hidden">
                                        <option value="cliente" {{ $user->role === 'cliente' ? 'selected' : '' }}>Cliente</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                                    </select>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="suspendUser({{ $user->id }})" title="{{ $user->status === 'activo' ? 'Suspender' : 'Activar' }}" class="p-1.5 text-gray-500 hover:text-amber-600 transition">
                                        @if($user->status === 'activo')
                                            <!-- Lock icon -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        @else
                                            <!-- Unlock icon -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </button>
                                    <button wire:confirm="¿Estás seguro que deseas eliminar este usuario permanentemente? Esta acción borrará su historial de compras." wire:click="deleteUser({{ $user->id }})" title="Eliminar" class="p-1.5 text-gray-500 hover:text-red-600 transition">
                                        <!-- Trash icon -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                No se encontraron usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Security Modal for Role Change -->
    @if($showRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 transition-all">
            <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-xl w-full max-w-sm overflow-hidden border border-gray-200 dark:border-neutral-800 p-6 transform transition-all">
                <div class="flex items-center gap-3 text-red-600 mb-4">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white leading-tight">Verificación de Seguridad</h3>
                </div>
                <p class="text-sm text-gray-600 dark:text-neutral-400 mb-5">Para otorgar o quitar poderes administrativos, por favor confirma tu identidad ingresando tu <strong>propia contraseña</strong>.</p>
                
                <div class="mb-6">
                    <input type="password" wire:model="passwordConfirmation" wire:keydown.enter="confirmRoleChange" placeholder="Escribe tu contraseña..." autofocus
                           class="w-full px-4 py-3 text-sm rounded-xl border border-gray-300 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:focus:bg-neutral-900 transition-colors">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button wire:click="cancelRoleChange" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        Cancelar
                    </button>
                    <button wire:click="confirmRoleChange" class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md transition-colors flex items-center gap-2">
                        <span>Autorizar</span>
                        <div wire:loading wire:target="confirmRoleChange" class="size-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
