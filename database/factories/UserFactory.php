<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'national_id' => $this->faker->unique->numerify('##########'), // 10-digit number as a string
            'photo' => 'https://via.placeholder.com/150', // example placeholder image
            'medical_council_id' => $this->faker->unique->numerify('MCId-#####'),
            'contract_number' => $this->faker->unique->numerify('CONTRACT-########'), // 16-digit contract number
            'email' => fake()->unique()->safeEmail(),
           'role' =>$this->faker->randomElement(['supperadmin','admin','user']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'address' => $this->faker->address(),
            'password' => static::$password ??= Hash::make('password'),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
