<?php

namespace Database\Seeders;
use App\Models\Cheque;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChequeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Cheque::factory()->count(10)->create();
        // You can also use the following line to create a specific number of cheques
        // \App\Models\Cheque::factory()->count(10)->create();
        // Or if you want to create a single cheque
        // \App\Models\Cheque::factory()->create();
    }
}
