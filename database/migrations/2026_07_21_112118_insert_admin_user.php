<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $admin = User::query()->where('email', 'admin@admin.com')->first();

        $attributes = [
            'name' => 'admin',
            'password' => 'admin',
            'secret_token' => 'admin',
            'email_verified_at' => now(),
        ];

        if ($admin) {
            $admin->forceFill($attributes)->save();

            return;
        }

        User::query()->create(array_merge($attributes, [
            'email' => 'admin@admin.com',
        ]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::query()->where('email', 'admin@admin.com')->delete();
    }
};
