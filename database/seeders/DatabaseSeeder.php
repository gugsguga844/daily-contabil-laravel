<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Office;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Criar alguns offices (sem owner por padrão)
        $offices = Office::factory()->count(3)->create();

        // 2) Definir um owner para os dois primeiros offices
        $offices->take(2)->each(function (Office $office) {
            $owner = User::factory()->create([
                'office_id' => $office->id,
                'role' => 'admin',
            ]);
            $office->update(['office_owner_id' => $owner->id]);
        });

        // 3) Criar usuários adicionais para cada office
        $offices->each(function (Office $office) {
            User::factory()->count(3)->create([
                'office_id' => $office->id,
            ]);
        });

        // 4) Criar companies em cada office, vinculando creator/accountant a usuários do mesmo office
        $offices->each(function (Office $office) {
            $users = User::where('office_id', $office->id)->get();

            $attrs = [
                'office_id' => $office->id,
            ];

            if ($users->isNotEmpty()) {
                $attrs['creator_id'] = optional($users->random())->id;
                $attrs['accountant_id'] = optional($users->random())->id;
            }

            Company::factory()->count(15)->create($attrs);
        });

        // 5) Usuário de teste padrão
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
