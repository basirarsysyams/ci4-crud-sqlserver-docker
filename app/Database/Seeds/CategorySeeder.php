<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'name' => 'Makanan',
                'description' => 'Produk makanan dan camilan',
            ],

            [
                'name' => 'Minuman',
                'description' => 'Produk minuman kemasan',
            ],

            [
                'name' => 'Elektronik',
                'description' => 'Perangkat elektronik',
            ],

            [
                'name' => 'Fashion',
                'description' => 'Pakaian dan aksesoris',
            ],

            [
                'name' => 'Kesehatan',
                'description' => 'Produk kesehatan dan kecantikan',
            ],

        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
