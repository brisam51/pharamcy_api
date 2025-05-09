<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    protected $model = \App\Models\Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_number' => $this->faker->unique()->bankAccountNumber(),
            'account_name' => $this->faker->name(),
            'branch_name' => $this->faker->city(),
            'account_type' => $this->faker->randomElement(['saving', 'current']),
            'balance' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'bank_id' => \App\Models\Bank::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
