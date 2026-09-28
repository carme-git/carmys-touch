<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('details_achats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achat_id')->constrained('achats')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('produit_id')->constrained('produits')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('sous_total', 10, 2)->storedAs('quantite * prix_unitaire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('details_achats');
    }
};