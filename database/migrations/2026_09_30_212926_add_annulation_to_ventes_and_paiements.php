<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dateTime('annulee_le')->nullable()->after('statut_paiement');
            $table->string('motif_annulation', 255)->nullable()->after('annulee_le');
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->dateTime('rembourse_le')->nullable()->after('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn(['annulee_le', 'motif_annulation']);
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn('rembourse_le');
        });
    }
};