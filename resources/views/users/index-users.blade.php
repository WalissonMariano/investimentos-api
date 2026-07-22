<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários — Investimentos API</title>
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
            color: var(--ink);
            background: #ffffff;
            padding: 1.75rem 1.5rem 2.5rem;
        }

        .page {
            width: min(100%, 1100px);
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .count-pill {
            font-size: 0.82rem;
            color: var(--ink-soft);
            background: var(--muted-bg);
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 0.45rem 0.85rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 6px;
            padding: 0.7rem 1.15rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.88rem;
            text-decoration: none;
            color: var(--ink);
            background: var(--yellow);
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .btn:hover {
            background: var(--yellow-deep);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .search {
            flex: 1;
            min-width: 220px;
            border: 1px solid var(--line);
            background: var(--muted-bg);
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font: inherit;
            font-size: 0.92rem;
            color: var(--ink);
        }

        .search:focus {
            outline: none;
            border-color: var(--yellow);
            box-shadow: 0 0 0 4px rgba(245, 197, 24, 0.28);
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th,
        td {
            text-align: left;
            padding: 0.95rem 1rem;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        th {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-soft);
            background: var(--muted-bg);
        }

        tr:last-child td {
            border-bottom: 0;
        }

        tbody tr:hover td {
            background: #fffdf5;
        }

        .user-name {
            font-weight: 600;
        }

        .user-id {
            font-size: 0.75rem;
            color: var(--ink-soft);
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .muted {
            color: var(--ink-soft);
            font-size: 0.85rem;
        }

        .empty {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--ink-soft);
        }

        .empty strong {
            display: block;
            color: var(--ink);
            font-size: 1.05rem;
            margin-bottom: 0.35rem;
        }

        @media (max-width: 640px) {
            body {
                padding: 1.25rem 1rem 2rem;
            }

            .col-id,
            .col-updated {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="page-header">
            <div>
                <span class="eyebrow">Cadastros</span>
                <h1>Usuários</h1>
            </div>
            <div class="header-actions">
                <span class="count-pill">{{ $users->count() }} cadastrado(s)</span>
                <a class="btn" href="#">Novo usuário</a>
            </div>
        </header>

        <div class="toolbar">
            <input
                type="search"
                class="search"
                id="user-search"
                placeholder="Buscar por nome ou e-mail..."
                autocomplete="off"
            >
        </div>

        <section class="panel">
            <div class="table-wrap">
                @if ($users->isEmpty())
                    <div class="empty">
                        <strong>Nenhum usuário cadastrado</strong>
                        <p>Cadastre o primeiro usuário para começar.</p>
                    </div>
                @else
                    <table id="users-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th class="col-id">ID</th>
                                <th>Criado em</th>
                                <th class="col-updated">Atualizado em</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr data-search="{{ strtolower($user->name.' '.$user->email) }}">
                                    <td>
                                        <div class="user-name">{{ $user->name }}</div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td class="col-id">
                                        <div class="user-id" title="{{ $user->id }}">{{ $user->id }}</div>
                                    </td>
                                    <td class="muted">{{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="col-updated muted">{{ $user->updated_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="empty" id="no-results" hidden>
                        <strong>Nenhum resultado</strong>
                        <p>Tente outro termo de busca.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <script>
        (function () {
            const input = document.getElementById('user-search');
            const table = document.getElementById('users-table');
            const noResults = document.getElementById('no-results');
            if (!input || !table) return;

            const rows = Array.from(table.querySelectorAll('tbody tr'));

            input.addEventListener('input', function () {
                const term = input.value.trim().toLowerCase();
                let visible = 0;

                rows.forEach((row) => {
                    const match = !term || (row.dataset.search || '').includes(term);
                    row.hidden = !match;
                    if (match) visible += 1;
                });

                table.hidden = visible === 0;
                if (noResults) noResults.hidden = visible > 0;
            });
        })();
    </script>
</body>
</html>
