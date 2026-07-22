<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $company = Company::query()->create([
            'name' => 'Minha Empresa',
        ]);

        $plainSecretToken = 'fgtrgtrgrgrtgrg.gtgtrgrtgrtgtrgrtgtrg.gtgtrgtrgrgtrgrt';

        User::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'secret_token' => $plainSecretToken,
            'company_id' => $company->id,
        ]);

        $this->command?->info('Usuário de integração criado:');
        $this->command?->info('email: admin@admin.com');
        $this->command?->info('secret_token: '.$plainSecretToken);
    }
}
