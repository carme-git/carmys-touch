<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journaux_activites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('action', 100);
            $table->string('entite', 100);
            $table->unsignedBigInteger('entite_id');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['entite', 'entite_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journaux_activites');
    }
};