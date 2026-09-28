<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->restrictOnDelete()->cascadeOnUpdate();
            $table->dateTime('date_vente');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('statut_paiement', ['paye', 'partiel', 'impaye'])->default('impaye');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};