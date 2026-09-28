<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    \App\Models\Client::insert([
        ['nom' => 'Houngbo', 'prenom' => 'Marie', 'telephone' => '0197111111'],
        ['nom' => 'Agossou', 'prenom' => 'Paul', 'telephone' => '0197222222'],
    ]);
}
}
