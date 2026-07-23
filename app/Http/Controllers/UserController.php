<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'user_group', 'created_at', 'updated_at']);

        return view('users.index-users', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('users.form-users');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $user): View
    {
        return view('users.form-users', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validatedData($request, $user);

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Você não pode excluir o próprio usuário.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }

    /**
     * @return array{name: string, email: string, user_group: string, password: string, secret_token: string}
     */
    private function validatedData(Request $request, ?User $user = null): array
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user?->id),
                ],
                'user_group' => ['required', Rule::in(['admin', 'user'])],
                'password' => ['required', 'confirmed', Password::defaults()],
                'secret_token' => ['required', 'string', 'min:8', 'max:255'],
            ],
            [
                'name.required' => 'Informe o nome.',
                'email.required' => 'Informe o e-mail.',
                'email.email' => 'Informe um e-mail válido.',
                'email.unique' => 'Este e-mail já está em uso.',
                'user_group.required' => 'Selecione o grupo.',
                'user_group.in' => 'Grupo inválido.',
                'password.required' => 'Informe a senha.',
                'password.confirmed' => 'A confirmação da senha não confere.',
                'secret_token.required' => 'Informe o secret token.',
                'secret_token.min' => 'O secret token deve ter no mínimo 8 caracteres.',
            ]
        );

        return [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'user_group' => $validated['user_group'],
            'password' => $validated['password'],
            'secret_token' => $validated['secret_token'],
        ];
    }
}
