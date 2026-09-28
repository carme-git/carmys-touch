<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $identifiants = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => "L'adresse e-mail est obligatoire.",
            'email.email'       => "L'adresse e-mail n'est pas valide.",
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        if (Auth::attempt($identifiants)) {
            // Nouvelle session après connexion : empêche le vol de session
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Même message que l'e-mail ou le mot de passe soit faux : on ne révèle pas lequel
        return back()
            ->withErrors(['email' => 'Adresse e-mail ou mot de passe incorrect.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}