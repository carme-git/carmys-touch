<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('quantite_theorique');
            $table->integer('quantite_constatee');
            $table->integer('ecart')->storedAs('quantite_constatee - quantite_theorique');
            $table->text('justification')->nullable();
            $table->date('date_inventaire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaires');
    }
};