<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * Reset page on search filter updates.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public $passwordConfirmation = '';
    public $targetUserId = null;
    public $targetRole = null;
    public $showRoleModal = false;

    /**
     * Trigger role change modal.
     */
    public function changeRole($userId, $newRole)
    {
        // Prevent self-lockout
        if ($userId === auth()->id()) {
            session()->flash('toast', [
                'type' => 'error',
                'message' => 'No puedes cambiar tu propio rol administrativo.',
            ]);
            return;
        }

        $this->targetUserId = $userId;
        $this->targetRole = $newRole;
        $this->showRoleModal = true;
    }

    public function confirmRoleChange()
    {
        if (!\Illuminate\Support\Facades\Hash::check($this->passwordConfirmation, auth()->user()->password)) {
            session()->flash('toast', ['type' => 'error', 'message' => 'Contraseña incorrecta. Operación cancelada.']);
            $this->cancelRoleChange();
            return;
        }

        $user = User::findOrFail($this->targetUserId);
        $user->update(['role' => $this->targetRole]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => "Rol de '{$user->name}' cambiado a '{$this->targetRole}'.",
        ]);

        $this->cancelRoleChange();
    }

    public function cancelRoleChange()
    {
        $this->showRoleModal = false;
        $this->passwordConfirmation = '';
        $this->targetUserId = null;
        $this->targetRole = null;
    }

    public function suspendUser($userId)
    {
        if ($userId === auth()->id()) {
            session()->flash('toast', ['type' => 'error', 'message' => 'No puedes suspender tu propia cuenta.']);
            return;
        }

        $user = User::findOrFail($userId);
        $newStatus = $user->status === 'suspendido' ? 'activo' : 'suspendido';
        $user->update(['status' => $newStatus]);

        session()->flash('toast', ['type' => 'success', 'message' => "El usuario '{$user->name}' ahora está {$newStatus}."]);
    }

    public function deleteUser($userId)
    {
        if ($userId === auth()->id()) {
            session()->flash('toast', ['type' => 'error', 'message' => 'No puedes eliminar tu propia cuenta.']);
            return;
        }

        $user = User::findOrFail($userId);
        $name = $user->name;
        $user->delete();

        session()->flash('toast', ['type' => 'success', 'message' => "El usuario '{$name}' ha sido eliminado permanentemente."]);
    }

    /**
     * Render view.
     */
    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->latest()->paginate(15);

        return view('livewire.admin.users', [
            'users' => $users,
        ])->layout('layouts.admin');
    }
}
