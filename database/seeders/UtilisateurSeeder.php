<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    $adminRoleId = \App\Models\Role::where('nom', 'Administrateur')->first()->id;
    $caissierRoleId = \App\Models\Role::where('nom', 'Caissier')->first()->id;

    \App\Models\Utilisateur::insert([
        [
            'role_id' => $adminRoleId,
            'nom' => 'Diallo',
            'prenom' => 'Awa',
            'email' => 'awa.diallo@carmys.com',
            'mot_de_passe' => bcrypt('password'),
            'statut' => 'actif',
        ],
        [
            'role_id' => $caissierRoleId,
            'nom' => 'Kone',
            'prenom' => 'Sana',
            'email' => 'sana.kone@carmys.com',
            'mot_de_passe' => bcrypt('password'),
            'statut' => 'actif',
        ],
    ]);
}
}
