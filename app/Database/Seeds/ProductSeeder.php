<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'category_id' => 1,
                'name' => 'Keripik Singkong',
                'price' => 15000,
            ],

            [
                'category_id' => 1,
                'name' => 'Cokelat Batang',
                'price' => 25000,
            ],

            [
                'category_id' => 2,
                'name' => 'Teh Botol',
                'price' => 5000,
            ],

            [
                'category_id' => 2,
                'name' => 'Kopi Susu',
                'price' => 18000,
            ],

            [
                'category_id' => 3,
                'name' => 'Earphone Bluetooth',
                'price' => 150000,
            ],

            [
                'category_id' => 4,
                'name' => 'Kaos Polos',
                'price' => 75000,
            ],

            [
                'category_id' => 5,
                'name' => 'Vitamin C 1000mg',
                'price' => 45000,
            ],

        ];

        $this->db->table('products')->insertBatch($data);
    }
}
