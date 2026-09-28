<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    $parfums = \App\Models\Categorie::where('nom', 'Parfums')->first()->id;
    $soins = \App\Models\Categorie::where('nom', 'Soins corporels')->first()->id;

    \App\Models\Produit::insert([
        [
            'category_id' => $parfums, 'nom' => 'Chanel N°5', 'reference' => 'PARF-001',
            'prix_achat' => 15000, 'prix_vente' => 22000, 'quantite_stock' => 10, 'seuil_alerte' => 3,
        ],
        [
            'category_id' => $parfums, 'nom' => 'Dior Sauvage', 'reference' => 'PARF-002',
            'prix_achat' => 18000, 'prix_vente' => 26000, 'quantite_stock' => 8, 'seuil_alerte' => 3,
        ],
        [
            'category_id' => $soins, 'nom' => 'Crème hydratante Nivea', 'reference' => 'SOIN-001',
            'prix_achat' => 2000, 'prix_vente' => 3500, 'quantite_stock' => 25, 'seuil_alerte' => 5,
        ],
    ]);
}
}

