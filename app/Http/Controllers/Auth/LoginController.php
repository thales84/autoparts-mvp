<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email ou mot de passe incorrect.']);
        }

        $request->session()->regenerate();

        if (Auth::user()->status === 'blocked') {
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors(['email' => 'Votre compte a été désactivé.']);
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Connexion réussie. Bienvenue ' . Auth::user()->name . ' !');
    }
}
