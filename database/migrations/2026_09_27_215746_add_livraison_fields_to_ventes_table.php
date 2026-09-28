<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->decimal('frais_livraison', 10, 2)
                  ->default(0)
                  ->after('montant_total');

            $table->enum('mode_livraison', ['soi_meme', 'service'])
                  ->nullable()
                  ->after('frais_livraison');
        });
    }

    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn(['frais_livraison', 'mode_livraison']);
        });
    }
};