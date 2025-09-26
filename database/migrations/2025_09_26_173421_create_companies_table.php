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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('fantasy_name');
            $table->string('cnpj');
            $table->string('phone');
            $table->string('email');
            $table->string('street');
            $table->string('number');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->boolean('is_active')->default(true);
            $table->foreignId('creator_id')->constrained('users')->setNullOnDelete();
            $table->foreignId('accountant_id')->constrained('users')->setNullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
