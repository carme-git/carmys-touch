<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->string('categorie', 100);
            $table->decimal('montant', 10, 2);
            $table->text('description')->nullable();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('date_depense');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};