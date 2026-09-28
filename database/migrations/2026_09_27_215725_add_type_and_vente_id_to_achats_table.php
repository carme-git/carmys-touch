<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->enum('type', ['commande_client', 'stock_avance'])
                  ->default('stock_avance')
                  ->after('utilisateur_id');

            $table->foreignId('vente_id')
                  ->nullable()
                  ->after('type')
                  ->constrained('ventes')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();

            $table->foreignId('fournisseur_id')
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->dropForeign(['vente_id']);
            $table->dropColumn(['type', 'vente_id']);
        });
    }
};