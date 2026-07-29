<?php

namespace App\Http\Controllers;

use App\Models\ApiRequestLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();
        $last30Start = $now->copy()->subDays(30);
        $prev30Start = $now->copy()->subDays(60);
        $monthStart = $now->copy()->startOfMonth();
        $weekStart = $now->copy()->subDays(7);
        $prevWeekStart = $now->copy()->subDays(14);

        $consultas30 = ApiRequestLog::query()
            ->where('action', 'termometro')
            ->where('created_at', '>=', $last30Start)
            ->count();

        $consultasPrev30 = ApiRequestLog::query()
            ->where('action', 'termometro')
            ->whereBetween('created_at', [$prev30Start, $last30Start])
            ->count();

        $usuariosAtivos = (int) ApiRequestLog::query()
            ->where('created_at', '>=', $last30Start)
            ->whereNotNull('user_id')
            ->selectRaw('COUNT(DISTINCT user_id) as total')
            ->value('total');

        $tokens30 = ApiRequestLog::query()
            ->where('action', 'auth.login')
            ->where('created_at', '>=', $last30Start)
            ->count();

        $tokensWeek = ApiRequestLog::query()
            ->where('action', 'auth.login')
            ->where('created_at', '>=', $weekStart)
            ->count();

        $tokensPrevWeek = ApiRequestLog::query()
            ->where('action', 'auth.login')
            ->whereBetween('created_at', [$prevWeekStart, $weekStart])
            ->count();

        $totalRequests30 = ApiRequestLog::query()
            ->where('created_at', '>=', $last30Start)
            ->count();

        $successRequests30 = ApiRequestLog::query()
            ->where('created_at', '>=', $last30Start)
            ->where('status_code', '<', 500)
            ->count();

        $uptime = $totalRequests30 > 0
            ? round(($successRequests30 / $totalRequests30) * 100, 1)
            : 100.0;

        $actionCounts = ApiRequestLog::query()
            ->select('action', DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $last30Start)
            ->groupBy('action')
            ->pluck('total', 'action');

        $chartItems = [
            [
                'label' => 'Termômetro',
                'value' => (int) ($actionCounts['termometro'] ?? 0),
                'class' => 'is-cdi',
            ],
            [
                'label' => 'Login',
                'value' => (int) ($actionCounts['auth.login'] ?? 0),
                'class' => 'is-ipca',
            ],
            [
                'label' => 'Auth me',
                'value' => (int) ($actionCounts['auth.me'] ?? 0),
                'class' => 'is-usd',
            ],
            [
                'label' => 'Falhas',
                'value' => (int) ($actionCounts['auth.failed'] ?? 0),
                'class' => '',
            ],
        ];

        $chartMax = max(1, ...array_column($chartItems, 'value'));

        foreach ($chartItems as &$item) {
            $item['percent'] = (int) round(($item['value'] / $chartMax) * 100);
        }
        unset($item);

        $recentLogs = ApiRequestLog::query()
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $usageRows = ApiRequestLog::query()
            ->select(
                'user_id',
                DB::raw('COUNT(*) as requests_month'),
                DB::raw('MAX(created_at) as last_request_at')
            )
            ->where('created_at', '>=', $monthStart)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('requests_month')
            ->limit(10)
            ->get();

        $users = User::query()
            ->whereIn('id', $usageRows->pluck('user_id'))
            ->get(['id', 'name', 'email'])
            ->keyBy('id');

        $usersUsage = $usageRows->map(function ($row) use ($users, $weekStart) {
            $lastRequestAt = $row->last_request_at
                ? Carbon::parse($row->last_request_at)
                : null;

            return [
                'user' => $users->get($row->user_id),
                'requests_month' => (int) $row->requests_month,
                'last_request_at' => $lastRequestAt,
                'active' => $lastRequestAt !== null && $lastRequestAt->gte($weekStart),
            ];
        });

        return view('dashboard.dashboard', [
            'updatedAt' => $now,
            'kpis' => [
                'consultas' => [
                    'value' => $consultas30,
                    'delta' => $this->deltaPercent($consultas30, $consultasPrev30),
                    'delta_label' => 'vs 30 dias anteriores',
                ],
                'usuarios' => [
                    'value' => $usuariosAtivos,
                    'delta_label' => 'com uso nos últimos 30 dias',
                ],
                'tokens' => [
                    'value' => $tokens30,
                    'delta' => $this->deltaPercent($tokensWeek, $tokensPrevWeek),
                    'delta_label' => 'vs semana anterior',
                    'week_value' => $tokensWeek,
                ],
                'uptime' => [
                    'value' => $uptime,
                    'delta_label' => $totalRequests30 > 0
                        ? "{$successRequests30}/{$totalRequests30} respostas < 500"
                        : 'Sem requisições no período',
                ],
            ],
            'chartItems' => $chartItems,
            'recentLogs' => $recentLogs,
            'usersUsage' => $usersUsage,
        ]);
    }

    private function deltaPercent(int|float $current, int|float $previous): ?float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
