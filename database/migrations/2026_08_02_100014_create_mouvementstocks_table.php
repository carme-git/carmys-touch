<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->restrictOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['entree', 'sortie', 'correction']);
            $table->integer('quantite');
            $table->foreignId('achat_id')->nullable()->constrained('achats')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vente_id')->nullable()->constrained('ventes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('inventaire_id')->nullable()->constrained('inventaires')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->restrictOnDelete()->cascadeOnUpdate();
            $table->dateTime('date_mouvement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};