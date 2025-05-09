<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bank>
 */
class BankFactory extends Factory
{
   protected $model= \App\Models\Bank::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          'name'=>$this->faker->company().' Bank',
          'code'=>$this->faker->unique()->bankAccountNumber(),
          'branch'=>$this->faker->city(),
          'phone'=>$this->faker->phoneNumber(),
          'email'=>$this->faker->unique()->safeEmail(),
          'address'=>$this->faker->address(),
          'status'=>$this->faker->randomElement(['active','inactive']),  
        ];
    }
}
