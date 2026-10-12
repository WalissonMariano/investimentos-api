<?php

namespace Database\Seeders;

use App\Models\ApiRequestLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'admin@admin.com';

    private const DEMO_USERS = [
        [
            'name' => 'Ana Silva',
            'email' => 'ana.silva@investimentos.demo',
        ],
        [
            'name' => 'Bruno Costa',
            'email' => 'bruno.costa@investimentos.demo',
        ],
        [
            'name' => 'Carla Mendes',
            'email' => 'carla.mendes@investimentos.demo',
        ],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $this->purgeDemoData();
            $this->seedDemoData();
        });
    }

    private function purgeDemoData(): void
    {
        $demoEmails = collect(self::DEMO_USERS)->pluck('email');

        $demoUserIds = User::query()
            ->whereIn('email', $demoEmails)
            ->pluck('id');

        if ($demoUserIds->isNotEmpty()) {
            ApiRequestLog::query()->whereIn('user_id', $demoUserIds)->delete();
            User::query()->whereIn('id', $demoUserIds)->delete();
        }

        ApiRequestLog::query()
            ->where('meta->demo', true)
            ->delete();
    }

    private function seedDemoData(): void
    {
        $admin = User::query()->where('email', self::ADMIN_EMAIL)->first();

        if (! $admin) {
            throw new \RuntimeException('Usuário admin@admin.com não encontrado. Rode as migrations primeiro.');
        }

        $now = now();
        $demoUsers = [];

        foreach (self::DEMO_USERS as $index => $demoUser) {
            $demoUsers[] = User::query()->create([
                'name' => $demoUser['name'],
                'email' => $demoUser['email'],
                'password' => 'password',
                'secret_token' => 'password',
                'user_group' => 'user',
                'email_verified_at' => $now->copy()->subDays(20 - $index),
            ]);
        }

        $this->seedApiRequestLogs($admin, $demoUsers, $now);
    }

    /**
     * @param  list<User>  $demoUsers
     */
    private function seedApiRequestLogs(User $admin, array $demoUsers, $now): void
    {
        $logs = [
            [
                'user' => $admin,
                'action' => 'auth.login',
                'method' => 'POST',
                'endpoint' => '/api/auth/login',
                'status_code' => 200,
                'days_ago' => 1,
                'meta' => ['email' => $admin->email],
            ],
            [
                'user' => $admin,
                'action' => 'auth.me',
                'method' => 'GET',
                'endpoint' => '/api/auth',
                'status_code' => 200,
                'days_ago' => 1,
                'meta' => null,
            ],
            [
                'user' => $admin,
                'action' => 'termometro',
                'method' => 'GET',
                'endpoint' => '/api/termometro',
                'status_code' => 200,
                'days_ago' => 1,
                'meta' => [
                    'valor' => 10000,
                    'dataInicio' => '2024-01-01',
                    'dataFim' => '2024-12-31',
                ],
            ],
            [
                'user' => $admin,
                'action' => 'termometro',
                'method' => 'GET',
                'endpoint' => '/api/termometro',
                'status_code' => 200,
                'days_ago' => 3,
                'meta' => [
                    'valor' => 25000,
                    'dataInicio' => '2023-01-01',
                    'dataFim' => '2023-12-31',
                ],
            ],
            [
                'user' => $admin,
                'action' => 'auth.login',
                'method' => 'POST',
                'endpoint' => '/api/auth/login',
                'status_code' => 200,
                'days_ago' => 5,
                'meta' => ['email' => $admin->email],
            ],
            [
                'user' => null,
                'action' => 'auth.failed',
                'method' => 'POST',
                'endpoint' => '/api/auth/login',
                'status_code' => 401,
                'days_ago' => 2,
                'meta' => ['email' => 'invalido@exemplo.com'],
            ],
            [
                'user' => null,
                'action' => 'auth.failed',
                'method' => 'POST',
                'endpoint' => '/api/auth/login',
                'status_code' => 401,
                'days_ago' => 8,
                'meta' => ['email' => 'teste@exemplo.com'],
            ],
        ];

        foreach ($demoUsers as $index => $user) {
            $logs[] = [
                'user' => $user,
                'action' => 'auth.login',
                'method' => 'POST',
                'endpoint' => '/api/auth/login',
                'status_code' => 200,
                'days_ago' => 2 + $index,
                'meta' => ['email' => $user->email],
            ];

            $logs[] = [
                'user' => $user,
                'action' => 'auth.me',
                'method' => 'GET',
                'endpoint' => '/api/auth',
                'status_code' => 200,
                'days_ago' => 2 + $index,
                'meta' => null,
            ];

            $logs[] = [
                'user' => $user,
                'action' => 'termometro',
                'method' => 'GET',
                'endpoint' => '/api/termometro',
                'status_code' => 200,
                'days_ago' => 4 + $index,
                'meta' => [
                    'valor' => 5000 * ($index + 1),
                    'dataInicio' => '2024-01-01',
                    'dataFim' => '2024-06-30',
                ],
            ];

            $logs[] = [
                'user' => $user,
                'action' => 'termometro',
                'method' => 'GET',
                'endpoint' => '/api/termometro',
                'status_code' => 200,
                'days_ago' => 10 + $index,
                'meta' => [
                    'valor' => 15000,
                    'dataInicio' => '2022-01-01',
                    'dataFim' => '2022-12-31',
                ],
            ];
        }

        $logs[] = [
            'user' => $demoUsers[0],
            'action' => 'termometro',
            'method' => 'GET',
            'endpoint' => '/api/termometro',
            'status_code' => 500,
            'days_ago' => 12,
            'meta' => [
                'valor' => 1000,
                'dataInicio' => '2020-01-01',
                'dataFim' => '2020-12-31',
                'error' => 'Falha ao consultar série do Banco Central.',
            ],
        ];

        $logs[] = [
            'user' => $admin,
            'action' => 'termometro',
            'method' => 'GET',
            'endpoint' => '/api/termometro',
            'status_code' => 200,
            'days_ago' => 15,
            'meta' => [
                'valor' => 50000,
                'dataInicio' => '2021-01-01',
                'dataFim' => '2024-12-31',
            ],
        ];

        $logs[] = [
            'user' => $admin,
            'action' => 'auth.login',
            'method' => 'POST',
            'endpoint' => '/api/auth/login',
            'status_code' => 200,
            'days_ago' => 20,
            'meta' => ['email' => $admin->email],
        ];

        $logs[] = [
            'user' => $demoUsers[1],
            'action' => 'auth.login',
            'method' => 'POST',
            'endpoint' => '/api/auth/login',
            'status_code' => 200,
            'days_ago' => 25,
            'meta' => ['email' => $demoUsers[1]->email],
        ];

        foreach ($logs as $log) {
            $meta = $log['meta'] ?? [];
            $meta['demo'] = true;

            ApiRequestLog::query()->create([
                'user_id' => $log['user']?->id,
                'action' => $log['action'],
                'method' => $log['method'],
                'endpoint' => $log['endpoint'],
                'status_code' => $log['status_code'],
                'ip' => '127.0.0.1',
                'meta' => $meta,
                'created_at' => $now->copy()->subDays($log['days_ago'])->subMinutes(random_int(0, 120)),
            ]);
        }
    }
}
