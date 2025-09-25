<?php

namespace Database\Seeders;

use App\Models\warehouses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => "Main Warehouse", 'address' => "Main Street", 'contact' => "1234567890"],
            ['name' => "Warehouse 1", 'address' => "Warehouse 1 Street", 'contact' => "1234567890"],

        ];
        warehouses::insert($data);
    }
}
