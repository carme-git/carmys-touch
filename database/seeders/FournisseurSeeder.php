<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Fournisseur::insert([
        ['nom' => 'Beauté Import SARL', 'telephone' => '0197000001', 'email' => 'contact@beauteimport.com'],
        ['nom' => 'Cosmex Distribution', 'telephone' => '0197000002', 'email' => 'contact@cosmex.com'],
    ]);
}
}
