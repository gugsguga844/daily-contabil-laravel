<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShowTables extends Command
{
    protected $signature = 'tables:show';
    protected $description = 'Lista todas as tabelas do banco de dados';

    public function handle()
    {
        $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
        
        if (empty($tables)) {
            $this->info('Nenhuma tabela encontrada no banco de dados.');
            return;
        }

        $this->info('Tabelas encontradas:');
        foreach ($tables as $table) {
            $this->line("- {$table->name}");
        }
    }
}