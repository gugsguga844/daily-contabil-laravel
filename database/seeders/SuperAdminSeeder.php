<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enabled = (string) env('SUPER_ADMIN_SEED', '0');
        if (! in_array(strtolower($enabled), ['1', 'true', 'yes', 'on'], true)) {
            return;
        }

        $email = trim((string) env('SUPER_ADMIN_EMAIL', ''));
        $password = (string) env('SUPER_ADMIN_PASSWORD', '');

        if ($email === '' || $password === '') {
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'office_id' => null,
            ]
        );
    }
}
