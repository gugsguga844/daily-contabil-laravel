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
        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign(['uploader_id']);
            $table->dropIndex(['uploader_id']);
            $table->dropColumn('uploader_id');
            $table->dropColumn('size_bytes');
        });
    }
};
