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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('fantasy_name');
            $table->string('cnpj')->unique();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('street');
            $table->string('number');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->boolean('is_active')->default(true);
            $table->foreignId('office_owner_id')->constrained('users')->onDelete('restrict');
            $table->string('current_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
