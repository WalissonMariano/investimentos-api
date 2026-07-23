<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($user) ? 'Editar usuário' : 'Novo usuário' }} — Investimentos API</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1c1608;
            --ink-soft: #4a3d1f;
            --yellow: #f5c518;
            --yellow-deep: #d4a017;
            --surface: #ffffff;
            --line: #e6d48a;
            --muted-bg: #fffef8;
            --negative: #b45309;
            --shadow: 0 12px 32px rgba(28, 22, 8, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.8125rem;
            color: var(--ink);
            background: #ffffff;
            padding: 1.15rem 1.1rem 1.75rem;
        }

        .page {
            width: 100%;
            max-width: none;
        }

        .page-header {
            margin-bottom: 0.9rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--line);
        }

        .eyebrow {
            display: block;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--yellow-deep);
            margin-bottom: 0.2rem;
        }

        h1 {
            font-size: clamp(1.05rem, 2.4vw, 1.25rem);
            font-weight: 500;
            letter-spacing: -0.03em;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 6px;
            box-shadow: var(--shadow);
            padding: 0.95rem 1rem;
        }

        .field {
            margin-bottom: 0.75rem;
        }

        label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            margin-bottom: 0.28rem;
            color: var(--ink-soft);
        }

        .hint {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.68rem;
            color: var(--ink-soft);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            border: 1px solid var(--line);
            background: var(--muted-bg);
            border-radius: 5px;
            padding: 0.45rem 0.7rem;
            font: inherit;
            font-size: 0.8rem;
            color: var(--ink);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234a3d1f' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            padding-right: 2rem;
            cursor: pointer;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--yellow);
            box-shadow: 0 0 0 3px rgba(245, 197, 24, 0.28);
        }

        .field-error {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.72rem;
            color: var(--negative);
        }

        .error-box {
            background: rgba(180, 83, 9, 0.1);
            border: 1px solid rgba(180, 83, 9, 0.25);
            color: #92400e;
            border-radius: 5px;
            padding: 0.5rem 0.7rem;
            font-size: 0.78rem;
            margin-bottom: 0.75rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.9rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--line);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 5px;
            padding: 0.4rem 0.75rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.75rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease, color 0.2s ease;
        }

        .btn-primary {
            color: var(--ink);
            background: var(--yellow);
        }

        .btn-primary:hover {
            background: var(--yellow-deep);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-secondary {
            color: var(--ink);
            background: var(--muted-bg);
            border: 1px solid var(--line);
        }

        .btn-secondary:hover {
            background: #fff8dc;
            transform: translateY(-1px);
        }

        @media (max-width: 560px) {
            body {
                padding: 0.9rem 0.75rem 1.4rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @php
        $isEdit = isset($user) && $user;
        $pageTitle = $isEdit ? 'Editar usuário' : 'Novo usuário';
    @endphp

    <div class="page">
        <header class="page-header">
            <span class="eyebrow">Cadastros</span>
            <h1>{{ $pageTitle }}</h1>
        </header>

        <section class="panel">
            @if (isset($errors) && $errors->any())
                <div class="error-box" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}"
            >
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="field">
                    <label for="name">Nome</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $isEdit ? $user->name : '') }}"
                        placeholder="Nome do usuário"
                        required
                        autofocus
                    >
                    @if (isset($errors) && $errors->has('name'))
                        <span class="field-error">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="field">
                    <label for="email">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $isEdit ? $user->email : '') }}"
                        placeholder="usuario@empresa.com"
                        required
                        autocomplete="username"
                    >
                    @if (isset($errors) && $errors->has('email'))
                        <span class="field-error">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="field">
                    <label for="user_group">Grupo</label>
                    @php
                        $selectedGroup = old('user_group', $isEdit ? $user->user_group : 'user');
                    @endphp
                    <select id="user_group" name="user_group" required>
                        <option value="user" @selected($selectedGroup === 'user')>Usuário</option>
                        <option value="admin" @selected($selectedGroup === 'admin')>Administrador</option>
                    </select>
                    @if (isset($errors) && $errors->has('user_group'))
                        <span class="field-error">{{ $errors->first('user_group') }}</span>
                    @endif
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="new-password"
                    >
                    @if (isset($errors) && $errors->has('password'))
                        <span class="field-error">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Repita a senha"
                        required
                        autocomplete="new-password"
                    >
                    @if (isset($errors) && $errors->has('password_confirmation'))
                        <span class="field-error">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

                <div class="field">
                    <label for="secret_token">Secret token</label>
                    <input
                        id="secret_token"
                        type="text"
                        name="secret_token"
                        value="{{ old('secret_token') }}"
                        placeholder="Token de integração da API"
                        required
                        autocomplete="off"
                    >
                    <span class="hint">Usado no login da API (e-mail + secret_token).</span>
                    @if (isset($errors) && $errors->has('secret_token'))
                        <span class="field-error">{{ $errors->first('secret_token') }}</span>
                    @endif
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Salvar alterações' : 'Criar usuário' }}
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </section>
    </div>
</body>
</html>
