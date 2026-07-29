<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página não encontrada — Investimentos API</title>
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
            display: grid;
            place-items: center;
            padding: 1.15rem;
        }

        .panel {
            width: min(100%, 420px);
            text-align: center;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 2rem 1.5rem;
        }

        .code {
            display: block;
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: -0.04em;
            color: var(--yellow-deep);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        h1 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.45rem;
        }

        p {
            font-size: 0.8rem;
            color: var(--ink-soft);
            line-height: 1.5;
            margin-bottom: 1.25rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 5px;
            padding: 0.45rem 0.85rem;
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
    </style>
</head>
<body>
    <section class="panel" role="alert">
        <span class="code" aria-hidden="true">404</span>
        <h1>Página não encontrada</h1>
        <p>
            O endereço que você acessou não existe ou foi movido.
            Verifique o link e tente novamente.
        </p>
        <div class="actions">
            @auth
                <a class="btn btn-primary" href="{{ route('menu') }}">Ir para o painel</a>
            @else
                <a class="btn btn-primary" href="{{ route('login.form') }}">Ir para o login</a>
            @endauth
            <button type="button" class="btn btn-secondary" onclick="history.back()">Voltar</button>
        </div>
    </section>
</body>
</html>
