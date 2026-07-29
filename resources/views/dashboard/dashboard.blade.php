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
        .kpi-delta.flat { color: var(--ink-soft); font-weight: 500; }

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

        .empty-inline {
            padding: 1rem 0.25rem;
            color: var(--ink-soft);
            font-size: 0.78rem;
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
    @php
        $actionLabels = [
            'auth.login' => 'Login API',
            'auth.failed' => 'Login falhou',
            'auth.me' => 'Consulta /auth',
            'termometro' => 'Consulta termômetro',
        ];
    @endphp

    <div class="page">
        <header class="page-header">
            <div>
                <span class="eyebrow">Visão geral</span>
                <h1>Dashboard</h1>
            </div>
            <p class="header-meta">
                Dados de api_request_logs<br>
                Atualizado em {{ $updatedAt->format('d/m/Y H:i') }}
            </p>
        </header>

        <section class="kpis" aria-label="Indicadores">
            <article class="kpi">
                <p class="kpi-label">Consultas termômetro (30 dias)</p>
                <p class="kpi-value">{{ number_format($kpis['consultas']['value'], 0, ',', '.') }}</p>
                @if ($kpis['consultas']['delta'] !== null)
                    <p class="kpi-delta {{ $kpis['consultas']['delta'] >= 0 ? 'up' : 'down' }}">
                        {{ $kpis['consultas']['delta'] >= 0 ? '+' : '' }}{{ $kpis['consultas']['delta'] }}% {{ $kpis['consultas']['delta_label'] }}
                    </p>
                @else
                    <p class="kpi-delta flat">{{ $kpis['consultas']['delta_label'] }}</p>
                @endif
            </article>
            <article class="kpi">
                <p class="kpi-label">Usuários ativos</p>
                <p class="kpi-value">{{ number_format($kpis['usuarios']['value'], 0, ',', '.') }}</p>
                <p class="kpi-delta flat">{{ $kpis['usuarios']['delta_label'] }}</p>
            </article>
            <article class="kpi">
                <p class="kpi-label">Tokens emitidos (30 dias)</p>
                <p class="kpi-value">{{ number_format($kpis['tokens']['value'], 0, ',', '.') }}</p>
                @if ($kpis['tokens']['delta'] !== null)
                    <p class="kpi-delta {{ $kpis['tokens']['delta'] >= 0 ? 'up' : 'down' }}">
                        {{ $kpis['tokens']['delta'] >= 0 ? '+' : '' }}{{ $kpis['tokens']['delta'] }}% {{ $kpis['tokens']['delta_label'] }}
                    </p>
                @else
                    <p class="kpi-delta flat">{{ $kpis['tokens']['delta_label'] }}</p>
                @endif
            </article>
            <article class="kpi">
                <p class="kpi-label">Taxa de sucesso</p>
                <p class="kpi-value">{{ number_format($kpis['uptime']['value'], 1, ',', '.') }}%</p>
                <p class="kpi-delta {{ $kpis['uptime']['value'] >= 99 ? 'up' : ($kpis['uptime']['value'] >= 95 ? 'flat' : 'down') }}">
                    {{ $kpis['uptime']['delta_label'] }}
                </p>
            </article>
        </section>

        <section class="grid">
            <article class="panel">
                <h2>Uso da API (30 dias)</h2>
                <p class="panel-desc">Volume relativo por tipo de ação registrada nos logs.</p>
                <div class="bars" aria-hidden="true">
                    @foreach ($chartItems as $item)
                        <div class="bar-col">
                            <div class="bar {{ $item['class'] }}" style="height: {{ max(6, $item['percent']) }}%"></div>
                            <span class="bar-label">{{ $item['label'] }}<br>{{ number_format($item['value'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="legend">
                    <span class="legend-item"><span class="dot cdi"></span> Termômetro</span>
                    <span class="legend-item"><span class="dot ipca"></span> Login</span>
                    <span class="legend-item"><span class="dot usd"></span> Auth me</span>
                    <span class="legend-item"><span class="dot"></span> Falhas</span>
                </div>
            </article>

            <article class="panel">
                <h2>Atividade recente</h2>
                <p class="panel-desc">Últimas ações registradas na API.</p>
                @if ($recentLogs->isEmpty())
                    <p class="empty-inline">Nenhum log registrado ainda.</p>
                @else
                    <ul class="list">
                        @foreach ($recentLogs as $log)
                            @php
                                $title = $actionLabels[$log->action] ?? $log->action;
                                $subtitle = $log->user?->email
                                    ?? ($log->meta['email'] ?? $log->endpoint);
                                if ($log->action === 'termometro' && isset($log->meta['valor'])) {
                                    $subtitle = 'R$ '.number_format((float) $log->meta['valor'], 2, ',', '.');
                                    if (! empty($log->meta['dataInicio']) && ! empty($log->meta['dataFim'])) {
                                        $subtitle .= ' · '.$log->meta['dataInicio'].' → '.$log->meta['dataFim'];
                                    }
                                }
                                $isOk = $log->status_code < 400;
                            @endphp
                            <li>
                                <div>
                                    <strong>{{ $title }}</strong>
                                    <span>{{ $subtitle }} · {{ $log->created_at?->diffForHumans() }}</span>
                                </div>
                                <span class="badge {{ $isOk ? '' : 'warn' }}">
                                    {{ $isOk ? 'OK' : $log->status_code }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>
        </section>

        <section class="panel table-panel">
            <h2>Usuários da API</h2>
            <p class="panel-desc">Consumo no mês atual com base em api_request_logs.</p>
            @if ($usersUsage->isEmpty())
                <p class="empty-inline">Nenhum uso registrado neste mês.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>E-mail</th>
                            <th>Requests / mês</th>
                            <th>Último acesso</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usersUsage as $row)
                            <tr>
                                <td>{{ $row['user']?->name ?? '—' }}</td>
                                <td>{{ $row['user']?->email ?? '—' }}</td>
                                <td>{{ number_format($row['requests_month'], 0, ',', '.') }}</td>
                                <td>{{ $row['last_request_at']?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td>
                                    <span class="status {{ $row['active'] ? '' : 'off' }}">
                                        {{ $row['active'] ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
</body>
</html>
