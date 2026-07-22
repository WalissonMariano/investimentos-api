<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    //listagem de usuários
    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'created_at', 'updated_at']);

        return view('users.index-users', [
            'users' => $users,
        ]);
    }

    //formulário de criação de usuário
    public function create(): View
    {
        return view('users.form-users');
    }

    //formulário de edição de usuário
    public function edit(User $user): View
    {
        return view('users.form-users', [
            'user' => $user,
        ]);
    }

    //salvar usuário
    public function store(Request $request): RedirectResponse
    {
        $user = User::create($request->all());
        return redirect()->route('users.index');
    }

    //atualizar usuário
    public function update(Request $request, User $user): RedirectResponse
    {
        $user->update($request->all());
        return redirect()->route('users.index');
    }

    //deletar usuário
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index');
    }

}
