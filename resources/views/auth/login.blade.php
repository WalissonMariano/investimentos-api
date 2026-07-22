<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso Admin — Investimentos API</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1c1608;
            --ink-soft: #4a3d1f;
            --yellow: #f5c518;
            --yellow-deep: #d4a017;
            --yellow-bg: #ffe566;
            --surface: #ffffff;
            --line: #e6d48a;
            --shadow: 0 20px 48px rgba(28, 22, 8, 0.12);
            --negative: #b45309;
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
            overflow-x: hidden;
        }

        .shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem 1.25rem;
        }

        .brand-logo {
            display: block;
            width: 100%;
            max-width: 200px;
            height: auto;
            margin: 0 auto 1.25rem;
        }

        .brand-sr {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .panel {
            width: min(100%, 460px);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 2.25rem 2rem 2rem;
            animation: rise 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--ink-soft);
        }

        .field {
            margin-bottom: 1.1rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            border: 1px solid var(--line);
            background: #fffef8;
            border-radius: 6px;
            padding: 0.85rem 1rem;
            font: inherit;
            font-size: 0.95rem;
            color: var(--ink);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--yellow);
            box-shadow: 0 0 0 4px rgba(245, 197, 24, 0.35);
            transform: translateY(-1px);
        }

        input.is-invalid {
            border-color: rgba(180, 83, 9, 0.55);
        }

        .error-box {
            background: rgba(180, 83, 9, 0.1);
            border: 1px solid rgba(180, 83, 9, 0.25);
            color: #92400e;
            border-radius: 6px;
            padding: 0.75rem 0.9rem;
            font-size: 0.88rem;
            margin-bottom: 1.15rem;
        }

        .field-error {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.8rem;
            color: var(--negative);
        }

        .actions {
            margin-top: 1.5rem;
        }

        button[type="submit"] {
            width: 100%;
            border: 0;
            border-radius: 6px;
            padding: 0.95rem 1.25rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 1rem;
            letter-spacing: -0.01em;
            color: var(--ink);
            background: var(--yellow);
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(212, 160, 23, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            background: var(--yellow-deep);
            color: #fff;
            box-shadow: 0 14px 30px rgba(212, 160, 23, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .hint {
            margin-top: 1.25rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--ink-soft);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fade {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 480px) {
            .panel {
                padding: 1.75rem 1.35rem 1.5rem;
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel" aria-labelledby="login-title">
            <h1 id="login-title" class="brand-sr">Termômetro do Poder de Compra</h1>
            <img
                class="brand-logo"
                src="{{ asset('images/img-api-investimentos.png') }}"
                alt="Termômetro do Poder de Compra — Dólar vs. CDI vs. IPCA"
            >

            <form action="{{ route('login') }}" method="post" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="error-box" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="field">
                    <label for="email">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@admin.com"
                        autocomplete="username"
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        autofocus
                    >
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    >
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="actions">
                    <button type="submit">Entrar no painel</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
