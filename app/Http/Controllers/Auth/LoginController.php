<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    //mostrar formulário de login
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    //processar login
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email deve ser um endereço de email válido',
            'password.required' => 'A senha é obrigatória',
        ]);

        if (! Auth::guard('web')->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'As credenciais fornecidas são inválidas',
            ])->onlyInput('email');
        }

        $user = Auth::guard('web')->user();

        if ($user?->user_group !== 'admin') {
            Auth::guard('web')->logout();

            return back()->withErrors([
                'email' => 'Apenas administradores podem acessar o painel.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended('/menu');
    }

    //deslogar
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
