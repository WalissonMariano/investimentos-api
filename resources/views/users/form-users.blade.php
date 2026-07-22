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
            color: var(--ink);
            background: #ffffff;
            padding: 1.75rem 1.5rem 2.5rem;
        }

        .page {
            width: min(100%, 640px);
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--line);
        }

        .eyebrow {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--yellow-deep);
            margin-bottom: 0.35rem;
        }

        h1 {
            font-size: clamp(1.4rem, 3vw, 1.85rem);
            font-weight: 500;
            letter-spacing: -0.03em;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 1.5rem 1.4rem;
        }

        .field {
            margin-bottom: 1.15rem;
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--ink-soft);
        }

        .hint {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.75rem;
            color: var(--ink-soft);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            border: 1px solid var(--line);
            background: var(--muted-bg);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            font: inherit;
            font-size: 0.95rem;
            color: var(--ink);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--yellow);
            box-shadow: 0 0 0 4px rgba(245, 197, 24, 0.28);
        }

        .field-error {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.8rem;
            color: var(--negative);
        }

        .error-box {
            background: rgba(180, 83, 9, 0.1);
            border: 1px solid rgba(180, 83, 9, 0.25);
            color: #92400e;
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            font-size: 0.88rem;
            margin-bottom: 1.15rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--line);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 999px;
            padding: 0.75rem 1.25rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.9rem;
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
                padding: 1.25rem 1rem 2rem;
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
                action="#"
                novalidate
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
                    <label for="password">Senha</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="{{ $isEdit ? 'Deixe em branco para manter' : '••••••••' }}"
                        @if (! $isEdit) required @endif
                        autocomplete="new-password"
                    >
                    @if ($isEdit)
                        <span class="hint">Preencha apenas se quiser alterar a senha.</span>
                    @endif
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
                        @if (! $isEdit) required @endif
                        autocomplete="new-password"
                    >
                </div>

                <div class="field">
                    <label for="secret_token">Secret token</label>
                    <input
                        id="secret_token"
                        type="text"
                        name="secret_token"
                        value="{{ old('secret_token') }}"
                        placeholder="{{ $isEdit ? 'Deixe em branco para manter' : 'Token de integração da API' }}"
                        @if (! $isEdit) required @endif
                        autocomplete="off"
                    >
                    @if ($isEdit)
                        <span class="hint">Preencha apenas se quiser gerar/alterar o token de integração.</span>
                    @else
                        <span class="hint">Usado no login da API (e-mail + secret_token).</span>
                    @endif
                    @if (isset($errors) && $errors->has('secret_token'))
                        <span class="field-error">{{ $errors->first('secret_token') }}</span>
                    @endif
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Salvar alterações' : 'Criar usuário' }}
                    </button>
                    <a href="{{ url('/users') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </section>
    </div>
</body>
</html>
