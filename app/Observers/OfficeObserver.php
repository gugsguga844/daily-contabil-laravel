<?php

namespace App\Observers;

use App\Models\Office;
use App\Models\Category;

class OfficeObserver
{
    /**
     * Handle the Office "created" event.
     */
    public function created(Office $office): void
    {
        $defaultCategories = [
            ['name' => 'Contábil', 'description' => 'Processos contábeis, lançamentos e demonstrações financeiros', 'icon_name' => 'Calculator', 'icon_color' => '#000'],
            ['name' => 'Fiscal', 'description' => 'Obrigações fiscais, apurações e declarações tributárias', 'icon_name' => 'Landmark', 'icon_color' => '#000'],
            ['name' => 'Departamento Pessoal', 'description' => 'Folha de pagamento, admissões, demissões e benefícios', 'icon_name' => 'UsersRound', 'icon_color' => '#000'],
            ['name' => 'Societário', 'description' => 'Alterações contratuais, atas e registros empresariais', 'icon_name' => 'UsersRoundCog', 'icon_color' => '#000'],
            ['name' => 'Processos Gerais', 'description' => 'Procedimentos administrativos e organizacionais', 'icon_name' => 'BookOpen', 'icon_color' => '#000'],
        ];

        foreach ($defaultCategories as $categoryData) {
            $office->categories()->create($categoryData);
        }
    }

    /**
     * Handle the Office "updated" event.
     */
    public function updated(Office $office): void
    {
        //
    }

    /**
     * Handle the Office "deleted" event.
     */
    public function deleted(Office $office): void
    {
        //
    }

    /**
     * Handle the Office "restored" event.
     */
    public function restored(Office $office): void
    {
        //
    }

    /**
     * Handle the Office "force deleted" event.
     */
    public function forceDeleted(Office $office): void
    {
        //
    }
}
