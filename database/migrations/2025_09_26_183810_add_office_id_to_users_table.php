<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make it nullable to avoid violating the FK for existing rows
            $table->foreignId('office_id')
                ->nullable()
                ->after('id')
                ->constrained('offices')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drops the foreign key and the column
            $table->dropConstrainedForeignId('office_id');
        });
    }
};
