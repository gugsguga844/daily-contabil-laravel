<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        // Doctrine DBAL is not installed in this project, so we use raw SQL.
        // These changes make optional Company fields nullable.
        DB::statement('ALTER TABLE `companies` MODIFY `fantasy_name` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `phone` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `email` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `street` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `number` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `city` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `state` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `zip_code` VARCHAR(255) NULL');
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        // Revert to NOT NULL as originally created in the initial migration.
        DB::statement('ALTER TABLE `companies` MODIFY `fantasy_name` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `phone` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `email` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `street` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `number` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `city` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `state` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE `companies` MODIFY `zip_code` VARCHAR(255) NOT NULL');
    }
};
