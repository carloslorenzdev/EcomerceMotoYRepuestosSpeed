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
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider text-center">Rol del Sistema</th>
                        <th class="px-4 py-3.5 font-bold uppercase tracking-wider">Fecha Registro</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 dark:divide-neutral-800 bg-white dark:bg-neutral-900/50">
                    @forelse ($users as $user)
                        <tr class="align-middle">
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

                            <!-- Created At -->
                            <td class="px-4 py-4 text-gray-500">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
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

</div>
