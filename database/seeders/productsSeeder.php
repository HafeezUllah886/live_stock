<?php

namespace Database\Seeders;

use App\Models\product_dc;
use App\Models\product_units;
use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
             ['name' => "Sheep", 'status' => 'Active'],
            ['name' => "Cattle", 'status' => 'Active'],
            ['name' => "Goat", 'status' => 'Active'],
           
        ];
        products::insert($data);
    }
}
