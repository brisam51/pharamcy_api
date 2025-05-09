<?php

namespace Database\Seeders;

use App\Models\Pharamcy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PharamcySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Pharamcy::factory()->count(10)->create();
    }
}
