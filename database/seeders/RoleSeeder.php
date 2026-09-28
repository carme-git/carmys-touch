<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    \App\Models\Role::insert([
        ['nom' => 'Administrateur', 'description' => 'Accès complet à la plateforme'],
        ['nom' => 'Gestionnaire', 'description' => 'Gestion des produits, stocks et rapports'],
        ['nom' => 'Caissier', 'description' => 'Enregistrement des ventes et paiements'],
        ['nom' => 'Magasinier', 'description' => 'Gestion des entrées et sorties de stock'],
    ]);
}
}
