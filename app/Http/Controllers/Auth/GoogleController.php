<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account consent'])
            ->redirect();
    }

    /**
     * Handle the callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Login
                Auth::login($user);
            } else {
                // Register new user
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Usuario Google',
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'client',
                    'password_set' => false,
                    'google_id' => $googleUser->getId(),
                ]);

                Auth::login($user);
            }

            // Redirect back to intended page or home
            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[GoogleAuthError] ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'No se pudo iniciar sesión con Google. Inténtalo de nuevo.',
            ]);
        }
    }
}
