<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel — Investimentos API</title>
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
            --sidebar-bg: #fffef8;
            --sidebar-text: #1c1608;
            --sidebar-muted: #8a7a4a;
            --shadow: 0 20px 48px rgba(28, 22, 8, 0.12);
            --sidebar-width: 220px;
            --sidebar-collapsed: 64px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.8125rem;
            color: var(--ink);
            background: #ffffff;
            overflow: hidden;
        }

        .app {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            flex: 0 0 var(--sidebar-width);
            width: var(--sidebar-width);
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            border-right: 2px solid var(--yellow);
            position: relative;
            z-index: 10;
            overflow: hidden;
            transition: flex-basis 0.25s ease, width 0.25s ease;
        }

        .app.is-sidebar-collapsed .sidebar {
            flex-basis: var(--sidebar-collapsed);
            width: var(--sidebar-collapsed);
        }

        .sidebar-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.4rem;
            padding: 0.75rem 0.6rem 0.7rem;
            border-bottom: 1px solid rgba(245, 197, 24, 0.25);
        }

        .sidebar-brand {
            min-width: 0;
            flex: 1;
            overflow: hidden;
            opacity: 1;
            transition: opacity 0.2s ease;
        }

        .sidebar-brand-logo {
            display: block;
            width: 100%;
            max-width: 78px;
            height: auto;
            border-radius: 5px;
            background: #ffffff;
        }

        .sidebar-collapse {
            flex-shrink: 0;
            width: 1.7rem;
            height: 1.7rem;
            border: 1px solid rgba(245, 197, 24, 0.45);
            border-radius: 5px;
            background: rgba(245, 197, 24, 0.12);
            color: var(--yellow);
            font-size: 0.85rem;
            line-height: 1;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.25s ease;
        }

        .sidebar-collapse:hover {
            background: var(--yellow);
            color: var(--ink);
        }

        .app.is-sidebar-collapsed .sidebar-collapse {
            transform: rotate(180deg);
            margin: 0 auto;
        }

        .app.is-sidebar-collapsed .sidebar-top {
            flex-direction: column;
            align-items: center;
            padding: 0.65rem 0.4rem;
        }

        .app.is-sidebar-collapsed .sidebar-brand {
            width: 0;
            height: 0;
            opacity: 0;
            pointer-events: none;
        }

        .sidebar-section {
            padding: 0.75rem 0.5rem 0.35rem;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-section-label {
            font-size: 0.58rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--sidebar-muted);
            padding: 0 0.55rem 0.45rem;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s ease;
        }

        .app.is-sidebar-collapsed .sidebar-section-label {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            width: 100%;
            text-align: left;
            border: 0;
            background: transparent;
            color: var(--sidebar-text);
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.78rem;
            padding: 0.48rem 0.6rem;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
            transition: background-color 0.2s ease, color 0.2s ease, padding 0.25s ease;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 18%;
            bottom: 18%;
            width: 2px;
            border-radius: 2px;
            background: transparent;
            transition: background-color 0.2s ease;
        }

        .nav-item:hover {
            background: rgba(245, 197, 24, 0.18);
            color: var(--ink);
        }

        .nav-item.is-active {
            background: var(--yellow);
            color: var(--ink);
        }

        .nav-item.is-active::before {
            background: var(--yellow-deep);
        }

        .nav-icon {
            width: 1.15rem;
            height: 1.15rem;
            border-radius: 4px;
            background: rgba(245, 197, 24, 0.28);
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: currentColor;
        }

        .nav-icon svg {
            width: 0.8rem;
            height: 0.8rem;
            display: block;
        }

        .nav-item.is-active .nav-icon {
            background: rgba(28, 22, 8, 0.18);
        }

        .nav-label {
            white-space: nowrap;
            overflow: hidden;
            opacity: 1;
            transition: opacity 0.15s ease;
        }

        .app.is-sidebar-collapsed .nav-item {
            justify-content: center;
            padding: 0.5rem 0.35rem;
        }

        .app.is-sidebar-collapsed .nav-label {
            width: 0;
            opacity: 0;
            position: absolute;
        }

        .sidebar-footer {
            padding: 0.65rem 0.55rem 0.85rem;
            border-top: 1px solid rgba(245, 197, 24, 0.25);
        }

        .btn-logout {
            width: 100%;
            border: 1px solid rgba(245, 197, 24, 0.45);
            border-radius: 5px;
            padding: 0.4rem 0.7rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.75rem;
            color: var(--ink);
            background: var(--yellow);
            cursor: pointer;
            transition: background-color 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .btn-logout:hover {
            background: var(--yellow-deep);
            color: #ffffff;
        }

        .app.is-sidebar-collapsed .btn-logout {
            padding: 0.4rem 0.25rem;
            font-size: 0.65rem;
        }

        /* ===== Conteúdo + iframe ===== */
        .workspace {
            flex: 1;
            min-width: 0;
            min-height: 0;
            display: flex;
            flex-direction: column;
            background: #ffffff;
        }

        .workspace-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            padding: 0.55rem 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--surface);
        }

        .workspace-title {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--ink);
        }

        .frame-wrap {
            flex: 1;
            min-height: 0;
            background: #ffffff;
        }

        .app-frame {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
            background: #ffffff;
        }

        .menu-toggle {
            display: none;
            border: 1px solid var(--line);
            background: var(--yellow);
            color: var(--ink);
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.75rem;
            border-radius: 5px;
            padding: 0.35rem 0.65rem;
            cursor: pointer;
        }

        .backdrop {
            display: none;
        }

        @media (max-width: 860px) {
            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                width: var(--sidebar-width) !important;
                flex-basis: var(--sidebar-width) !important;
                transform: translateX(-105%);
                transition: transform 0.25s ease;
                box-shadow: var(--shadow);
            }

            .app.is-menu-open .sidebar {
                transform: translateX(0);
            }

            .app.is-sidebar-collapsed .sidebar-brand,
            .app.is-sidebar-collapsed .sidebar-section-label,
            .app.is-sidebar-collapsed .nav-label {
                width: auto;
                height: auto;
                opacity: 1;
                position: static;
                pointer-events: auto;
            }

            .app.is-sidebar-collapsed .nav-item {
                justify-content: flex-start;
                padding: 0.48rem 0.6rem;
            }

            .app.is-sidebar-collapsed .sidebar-top {
                flex-direction: row;
                align-items: flex-start;
                padding: 0.75rem 0.6rem 0.7rem;
            }

            .app.is-sidebar-collapsed .sidebar-collapse {
                display: none;
            }

            .menu-toggle {
                display: inline-flex;
            }

            .backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(28, 22, 8, 0.4);
                z-index: 5;
            }

            .app.is-menu-open .backdrop {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="app" id="app-shell">
        <div class="backdrop" id="menu-backdrop" aria-hidden="true"></div>

        <aside class="sidebar" id="sidebar" aria-label="Menu lateral">
            <div class="sidebar-top">
                <div class="sidebar-brand">
                    <img
                        class="sidebar-brand-logo"
                        src="{{ asset('images/img-api-investimentos.png') }}"
                        alt="Termômetro do Poder de Compra"
                    >
                </div>
                <button
                    type="button"
                    class="sidebar-collapse"
                    id="sidebar-collapse"
                    title="Retrair menu"
                    aria-label="Retrair ou expandir menu"
                    aria-expanded="true"
                >
                    ‹
                </button>
            </div>

            <div class="sidebar-section">
                <p class="sidebar-section-label">Menu</p>
                <nav class="nav" id="app-nav">
                    <button
                        type="button"
                        class="nav-item is-active"
                        data-title="Início"
                        data-src="{{ url('/dashboard') }}"
                        title="Início"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 10.5 12 3l9 7.5"></path>
                                <path d="M5 9.5V21h14V9.5"></path>
                            </svg>
                        </span>
                        <span class="nav-label">Início</span>
                    </button>
                    <button
                        type="button"
                        class="nav-item"
                        data-title="Usuários"
                        data-src="{{ url('/users') }}"
                        title="Usuários"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </span>
                        <span class="nav-label">Usuários</span>
                    </button>
                </nav>
            </div>

            <div class="sidebar-footer">
                <form action="{{ route('auth.logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn-logout" title="Sair">Sair</button>
                </form>
            </div>
        </aside>

        <section class="workspace">
            <header class="workspace-header">
                <button type="button" class="menu-toggle" id="menu-toggle">Menu</button>
                <h1 class="workspace-title" id="workspace-title">Início</h1>
            </header>

            <div class="frame-wrap">
                <iframe
                    class="app-frame"
                    id="app-frame"
                    title="Área da aplicação"
                    src="{{ url('/dashboard') }}"
                ></iframe>
            </div>
        </section>
    </div>

    <script>
        (function () {
            const shell = document.getElementById('app-shell');
            const frame = document.getElementById('app-frame');
            const titleEl = document.getElementById('workspace-title');
            const nav = document.getElementById('app-nav');
            const toggle = document.getElementById('menu-toggle');
            const collapseBtn = document.getElementById('sidebar-collapse');
            const backdrop = document.getElementById('menu-backdrop');
            const homeButton = nav.querySelector('.nav-item[data-title="Início"]');
            const storageKey = 'investimentos.sidebarCollapsed';

            function setCollapsed(collapsed) {
                shell.classList.toggle('is-sidebar-collapsed', collapsed);
                collapseBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                collapseBtn.title = collapsed ? 'Expandir menu' : 'Retrair menu';
                localStorage.setItem(storageKey, collapsed ? '1' : '0');
            }

            function openApp(button, forceReload = false) {
                const title = button.dataset.title || 'Aplicação';
                const src = button.dataset.src || 'about:blank';

                nav.querySelectorAll('.nav-item').forEach((item) => {
                    item.classList.toggle('is-active', item === button);
                });

                titleEl.textContent = title;

                if (forceReload && frame.getAttribute('src') === src) {
                    frame.src = 'about:blank';
                    frame.onload = function () {
                        frame.onload = null;
                        frame.src = src;
                    };
                } else {
                    frame.src = src;
                }

                shell.classList.remove('is-menu-open');
            }

            nav.addEventListener('click', (event) => {
                const button = event.target.closest('.nav-item');
                if (!button) return;
                openApp(button, true);
            });

            collapseBtn.addEventListener('click', () => {
                setCollapsed(!shell.classList.contains('is-sidebar-collapsed'));
            });

            toggle.addEventListener('click', () => {
                shell.classList.toggle('is-menu-open');
            });

            backdrop.addEventListener('click', () => {
                shell.classList.remove('is-menu-open');
            });

            if (localStorage.getItem(storageKey) === '1') {
                setCollapsed(true);
            }

            if (homeButton) {
                openApp(homeButton, false);
            }
        })();
    </script>
</body>
</html>
