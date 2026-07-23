<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Investimentos API</title>
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
            --positive: #1f7a4d;
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

        .header-meta {
            font-size: 0.72rem;
            color: var(--ink-soft);
            text-align: right;
            line-height: 1.35;
        }

        .kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.65rem;
            margin-bottom: 0.85rem;
        }

        .kpi {
            background: var(--muted-bg);
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 0.7rem 0.8rem;
            box-shadow: var(--shadow);
        }

        .kpi-label {
            font-size: 0.68rem;
            color: var(--ink-soft);
            margin-bottom: 0.3rem;
        }

        .kpi-value {
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.15;
            margin-bottom: 0.25rem;
        }

        .kpi-delta {
            font-size: 0.68rem;
            font-weight: 600;
        }

        .kpi-delta.up { color: var(--positive); }
        .kpi-delta.down { color: var(--negative); }

        .grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 0.65rem;
            margin-bottom: 0.85rem;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 0.85rem 0.95rem;
            box-shadow: var(--shadow);
        }

        .panel h2 {
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .panel-desc {
            font-size: 0.72rem;
            color: var(--ink-soft);
            margin-bottom: 0.7rem;
        }

        .bars {
            display: flex;
            align-items: flex-end;
            gap: 0.5rem;
            height: 120px;
            padding-top: 0.35rem;
        }

        .bar-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
            height: 100%;
            justify-content: flex-end;
        }

        .bar {
            width: 100%;
            border-radius: 6px 6px 3px 3px;
            background: var(--yellow);
            min-height: 6px;
        }

        .bar.is-cdi { background: var(--yellow-deep); }
        .bar.is-ipca { background: #f0e2a0; }
        .bar.is-usd { background: #1c1608; }

        .bar-label {
            font-size: 0.62rem;
            color: var(--ink-soft);
            text-align: center;
            line-height: 1.25;
        }

        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-top: 0.7rem;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.68rem;
            color: var(--ink-soft);
        }

        .dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 999px;
            background: var(--yellow);
        }

        .dot.cdi { background: var(--yellow-deep); }
        .dot.ipca { background: #f0e2a0; }
        .dot.usd { background: #1c1608; }

        .list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .list li {
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            align-items: center;
            padding: 0.5rem 0.65rem;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: var(--muted-bg);
        }

        .list strong {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 0.1rem;
        }

        .list span {
            font-size: 0.68rem;
            color: var(--ink-soft);
        }

        .badge {
            flex-shrink: 0;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.22rem 0.45rem;
            border-radius: 5px;
            background: var(--yellow);
            color: var(--ink);
        }

        .badge.warn {
            background: #fff3cd;
            color: var(--negative);
            border: 1px solid #f0d78c;
        }

        .table-panel {
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
            padding: 0.45rem 0.5rem;
            border-bottom: 1px solid var(--line);
            line-height: 1.3;
        }

        th {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--ink-soft);
            padding: 0.4rem 0.5rem;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .status {
            display: inline-block;
            padding: 0.18rem 0.4rem;
            border-radius: 5px;
            font-size: 0.62rem;
            font-weight: 600;
            background: #e8f6ee;
            color: var(--positive);
        }

        .status.off {
            background: #fff3cd;
            color: var(--negative);
        }

        @media (max-width: 960px) {
            .kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            body {
                padding: 0.9rem 0.75rem 1.4rem;
            }

            .kpis {
                grid-template-columns: 1fr;
            }

            .header-meta {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="page-header">
            <div>
                <span class="eyebrow">Visão geral</span>
                <h1>Dashboard</h1>
            </div>
            <p class="header-meta">
                Exemplo de painel<br>
                Atualizado em 21/07/2026
            </p>
        </header>

        <section class="kpis" aria-label="Indicadores">
            <article class="kpi">
                <p class="kpi-label">Consultas (30 dias)</p>
                <p class="kpi-value">1.284</p>
                <p class="kpi-delta up">+12,4% vs mês anterior</p>
            </article>
            <article class="kpi">
                <p class="kpi-label">Empresas ativas</p>
                <p class="kpi-value">38</p>
                <p class="kpi-delta up">+3 novas</p>
            </article>
            <article class="kpi">
                <p class="kpi-label">Tokens emitidos</p>
                <p class="kpi-value">612</p>
                <p class="kpi-delta down">-4,1% vs semana</p>
            </article>
            <article class="kpi">
                <p class="kpi-label">Uptime API</p>
                <p class="kpi-value">99,8%</p>
                <p class="kpi-delta up">Estável</p>
            </article>
        </section>

        <section class="grid">
            <article class="panel">
                <h2>Termômetro — exemplo</h2>
                <p class="panel-desc">Variação acumulada no período (dados fictícios).</p>
                <div class="bars" aria-hidden="true">
                    <div class="bar-col">
                        <div class="bar is-cdi" style="height: 72%"></div>
                        <span class="bar-label">CDI<br>10%</span>
                    </div>
                    <div class="bar-col">
                        <div class="bar is-ipca" style="height: 42%"></div>
                        <span class="bar-label">IPCA<br>5%</span>
                    </div>
                    <div class="bar-col">
                        <div class="bar is-usd" style="height: 86%"></div>
                        <span class="bar-label">USD<br>12%</span>
                    </div>
                    <div class="bar-col">
                        <div class="bar" style="height: 58%"></div>
                        <span class="bar-label">Real<br>0%</span>
                    </div>
                </div>
                <div class="legend">
                    <span class="legend-item"><span class="dot cdi"></span> CDI</span>
                    <span class="legend-item"><span class="dot ipca"></span> IPCA</span>
                    <span class="legend-item"><span class="dot usd"></span> Dólar</span>
                    <span class="legend-item"><span class="dot"></span> Caixa</span>
                </div>
            </article>

            <article class="panel">
                <h2>Atividade recente</h2>
                <p class="panel-desc">Últimas ações no painel.</p>
                <ul class="list">
                    <li>
                        <div>
                            <strong>Login API — Minha Empresa</strong>
                            <span>há 12 minutos</span>
                        </div>
                        <span class="badge">OK</span>
                    </li>
                    <li>
                        <div>
                            <strong>Consulta termômetro</strong>
                            <span>R$ 10.000 · 2024</span>
                        </div>
                        <span class="badge">OK</span>
                    </li>
                    <li>
                        <div>
                            <strong>Token expirado</strong>
                            <span>user@cliente.com</span>
                        </div>
                        <span class="badge warn">Atenção</span>
                    </li>
                    <li>
                        <div>
                            <strong>Nova empresa cadastrada</strong>
                            <span>há 2 horas</span>
                        </div>
                        <span class="badge">OK</span>
                    </li>
                </ul>
            </article>
        </section>

        <section class="panel table-panel">
            <h2>Integrações</h2>
            <p class="panel-desc">Empresas consumindo a API (exemplo).</p>
            <table>
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Plano</th>
                        <th>Requests / mês</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Minha Empresa</td>
                        <td>Pro</td>
                        <td>4.820</td>
                        <td><span class="status">Ativo</span></td>
                    </tr>
                    <tr>
                        <td>Carteira Norte</td>
                        <td>Starter</td>
                        <td>910</td>
                        <td><span class="status">Ativo</span></td>
                    </tr>
                    <tr>
                        <td>Alpha Invest</td>
                        <td>Pro</td>
                        <td>0</td>
                        <td><span class="status off">Inativo</span></td>
                    </tr>
                    <tr>
                        <td>Beta Capital</td>
                        <td>Business</td>
                        <td>12.340</td>
                        <td><span class="status">Ativo</span></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
