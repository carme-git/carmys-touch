<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('nom_entreprise', 150);
            $table->string('logo')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('devise', 10)->default('FCFA');
            $table->string('numero_fiscal', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};