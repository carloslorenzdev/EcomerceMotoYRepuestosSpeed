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

    /**
     * Change user role.
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

        $user = User::findOrFail($userId);
        $user->update(['role' => $newRole]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => "Rol de '{$user->name}' cambiado a '{$newRole}'.",
        ]);
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
