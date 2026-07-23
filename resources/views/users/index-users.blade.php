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
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 0.65rem;
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .count-pill {
            font-size: 0.72rem;
            color: var(--ink-soft);
            background: var(--muted-bg);
            border: 1px solid var(--line);
            border-radius: 5px;
            padding: 0.3rem 0.6rem;
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
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .search {
            flex: 1;
            min-width: 180px;
            border: 1px solid var(--line);
            background: var(--muted-bg);
            border-radius: 5px;
            padding: 0.45rem 0.7rem;
            font: inherit;
            font-size: 0.8rem;
            color: var(--ink);
        }

        .search:focus {
            outline: none;
            border-color: var(--yellow);
            box-shadow: 0 0 0 3px rgba(245, 197, 24, 0.28);
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 6px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
        }

        th,
        td {
            text-align: left;
            padding: 0.45rem 0.65rem;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
            line-height: 1.3;
        }

        th {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--ink-soft);
            background: var(--muted-bg);
            padding: 0.4rem 0.65rem;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        tbody tr:hover td {
            background: #fffdf5;
        }

        .col-actions {
            width: 4.2rem;
            padding-left: 0.55rem;
            padding-right: 0.2rem;
        }

        .row-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.55rem;
            height: 1.55rem;
            border: 0;
            border-radius: 4px;
            color: var(--ink-soft);
            background: transparent;
            text-decoration: none;
            cursor: pointer;
            padding: 0;
            font: inherit;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-icon:hover {
            background: rgba(245, 197, 24, 0.28);
            color: var(--ink);
        }

        .btn-icon.is-danger:hover {
            background: rgba(180, 83, 9, 0.15);
            color: #92400e;
        }

        .btn-icon svg {
            width: 0.85rem;
            height: 0.85rem;
            display: block;
        }

        .alert {
            border-radius: 5px;
            padding: 0.55rem 0.75rem;
            font-size: 0.78rem;
            margin-bottom: 0.85rem;
        }

        .alert-success {
            background: #e8f6ee;
            border: 1px solid #b7e0c5;
            color: #1f7a4d;
        }

        .alert-error {
            background: rgba(180, 83, 9, 0.1);
            border: 1px solid rgba(180, 83, 9, 0.25);
            color: #92400e;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.8rem;
        }

        .user-id {
            font-size: 0.68rem;
            color: var(--ink-soft);
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-group {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            background: var(--muted-bg);
            border: 1px solid var(--line);
            color: var(--ink-soft);
            white-space: nowrap;
        }

        .user-group.is-admin {
            background: rgba(245, 197, 24, 0.22);
            border-color: var(--yellow);
            color: var(--ink);
        }

        .muted {
            color: var(--ink-soft);
            font-size: 0.74rem;
        }

        .empty {
            text-align: center;
            padding: 2rem 1.25rem;
            color: var(--ink-soft);
            font-size: 0.8rem;
        }

        .empty strong {
            display: block;
            color: var(--ink);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        @media (max-width: 640px) {
            body {
                padding: 0.9rem 0.75rem 1.4rem;
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
                <a class="btn" href="{{ route('users.create') }}">Novo usuário</a>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" role="alert">{{ session('error') }}</div>
        @endif

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
                                <th class="col-actions" aria-label="Ações"></th>
                                <th class="col-id">ID</th>
                                <th>Grupo</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Criado em</th>
                                <th class="col-updated">Atualizado em</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr data-search="{{ strtolower($user->name.' '.$user->email.' '.$user->user_group) }}">
                                    <td class="col-actions">
                                        <div class="row-actions">
                                            <a
                                                class="btn-icon"
                                                href="{{ route('users.edit', $user) }}"
                                                title="Editar {{ $user->name }}"
                                                aria-label="Editar {{ $user->name }}"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                                </svg>
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route('users.destroy', $user) }}"
                                                onsubmit="return confirm('Excluir o usuário {{ $user->name }}?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn-icon is-danger"
                                                    title="Excluir {{ $user->name }}"
                                                    aria-label="Excluir {{ $user->name }}"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M3 6h18" />
                                                        <path d="M8 6V4h8v2" />
                                                        <path d="M19 6l-1 14H6L5 6" />
                                                        <path d="M10 11v6" />
                                                        <path d="M14 11v6" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="col-id">
                                        <div class="user-id" title="{{ $user->id }}">{{ $user->id }}</div>
                                    </td>
                                    <td>
                                        <span class="user-group {{ $user->user_group === 'admin' ? 'is-admin' : '' }}">
                                            {{ $user->user_group === 'admin' ? 'Administrador' : 'Usuário' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-name">{{ $user->name }}</div>
                                    </td>
                                    <td>{{ $user->email }}</td>
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
