<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cheque>
 */
class ChequeFactory extends Factory
{
protected $model = \App\Models\Cheque::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cheque_number' => $this->faker->unique()->numerify('CHQ-#####'),
            'issue_date' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'payee_name' => $this->faker->name(),//invoice_payment', 'salary_payment', 'expanses'
            'status' => $this->faker->randomElement(['cleared', 'pending', 'returned']),
            'payment_type' => $this->faker->randomElement(['invoice_payment', 'salary_payment', 'expanses']),
            'reference_number' => $this->faker->optional()->word(),
            'description' => $this->faker->optional()->sentence(),
            'attachment' => $this->faker->optional()->imageUrl(),
            'is_void' => $this->faker->boolean(),
            'user_id' => \App\Models\User::factory(), // Assuming you have a User factory
            'account_id' => \App\Models\Account::factory(), // Assuming you have an Account factory
        ];
    }
}
