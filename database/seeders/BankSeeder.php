<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Bank::factory(10)->create();
        // You can also use the following line to create a specific number of banks
        // \App\Models\Bank::factory()->count(10)->create();
        // Or if you want to create a single bank
        // \App\Models\Bank::factory()->create();
    }
}
