<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

class SetPassword extends Component
{
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Rules.
     */
    protected $rules = [
        'password' => 'required|string|min:8|confirmed',
    ];

    /**
     * Custom validation messages.
     */
    protected $messages = [
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];

    /**
     * Save the password.
     */
    public function save()
    {
        $this->validate();

        $user = Auth::user();
        $user->password = Hash::make($this->password);
        $user->password_set = true;
        $user->save();

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Contraseña establecida exitosamente. ¡Bienvenido a MotoSpeed!',
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Render the view.
     */
    public function render()
    {
        return view('livewire.auth.set-password')
            ->layout('layouts.auth');
    }
}
