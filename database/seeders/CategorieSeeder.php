<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Categorie::insert([
        ['nom' => 'Parfums', 'description' => 'Parfums homme et femme'],
        ['nom' => 'Soins corporels', 'description' => 'Crèmes, lotions, savons'],
        ['nom' => 'Maquillage', 'description' => 'Produits de maquillage'],
        ['nom' => 'Accessoires', 'description' => 'Accessoires de beauté et hygiène'],
    ]);
}
}
