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
        Schema::table('contents', function (Blueprint $table) {
            $table->foreignId('uploader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('size_bytes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use SQLite-safe drops. Check column existence and rely on dropConstrainedForeignId
        // to remove the foreign key (and associated index) before dropping the column.
        if (Schema::hasColumn('contents', 'uploader_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropConstrainedForeignId('uploader_id');
            });
        }

        if (Schema::hasColumn('contents', 'size_bytes')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('size_bytes');
            });
        }
    }
};
