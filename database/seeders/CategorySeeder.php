<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?int $officeId = null): void
    {
        if (is_null($officeId)) {
            return;
        }

        $defaultCategories = [
            [
                'name' => 'Contábil',
                'description' => 'Processos contábeis, lançamentos e demonstrações financeiras',
                'icon_name' => 'Calculator',
                'icon_color' => '#3B82F6',
            ],
            [
                'name' => 'Fiscal',
                'description' => 'Obrigações fiscais, apurações e declarações tributárias',
                'icon_name' => 'Landmark',
                'icon_color' => '#10B981',
            ],
            [
                'name' => 'Departamento Pessoal',
                'description' => 'Folha de pagamento, admissões, demissões e benefícios',
                'icon_name' => 'UsersRound',
                'icon_color' => '#8B5CF6',
            ],
            [
                'name' => 'Societário',
                'description' => 'Alterações contratuais, atas e registros empresariais',
                'icon_name' => 'UserRoundCog',
                'icon_color' => '#F97316',
            ],
            [
                'name' => 'Processos Gerais',
                'description' => 'Procedimentos administrativos e organizacionais',
                'icon_name' => 'BookOpen',
                'icon_color' => '#64748B',
            ],
        ];

        foreach ($defaultCategories as $category) {
            Category::create([
                'office_id' => $officeId,
                'name' => $category['name'],
                'description' => $category['description'],
                'icon_name' => $category['icon_name'],
                'icon_color' => $category['icon_color'],
            ]);
        }
    }
}
