<?php

namespace Database\Seeders;

use App\Models\accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class accountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        accounts::create([
            'title'     => "Cash Account",
            'type'      => "Cash",
            'category'  => "Business",
            'status'    => "Active",
        ]);

        accounts::create([
            'title'     => "Walk-In Vendor",
            'category'  => "Vendor",
            'status'    => "Active",
        ]);

        accounts::create([
            'title'     => "Walk-In Customer",
            'category'  => "Customer",
            'status'    => "Active",
        ]);

    }
}
