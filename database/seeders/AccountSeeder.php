<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Account::factory(10)->create();
        // You can also use the following line to create a specific number of accounts
        // \App\Models\Account::factory()->count(10)->create();
        // Or if you want to create a single account
        // \App\Models\Account::factory()->create();
    }
}
