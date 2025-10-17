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
        if (! Schema::hasTable('steps')) {
            Schema::create('steps', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('title');
                $table->string('description')->nullable();
                $table->unsignedInteger('order');
                $table->foreignId('tutorial_id')->constrained('tutorials')->cascadeOnDelete();
                $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
                $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
