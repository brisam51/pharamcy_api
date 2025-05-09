<?php

namespace Database\Factories;
use App\Models\Pharamcy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pharamcy>
 */
class PharamcyFactory extends Factory
{
    protected $model = Pharamcy::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate= $this->faker->dateTimeBetween("-2 years","now");
        return [
             'user_id'=>User::factory(),
            "name"=>$this->faker->company.'pharamcy',
            'national_id'=>$this->faker->numerify('NATION-ID-########'),
            'address'=>$this->faker->address,
            'phone'=>$this->faker->phoneNumber(10),
            'email'=>$this->faker->email,
            'logo'=>$this->faker->imageUrl(200, 200, 'business', true, 'logo'),
            'status'=>$this->faker->randomElement(['active','inactive']),
            'subscription_start_date'=>$startDate->format('Y-m-d'),
            'subscription_end_date'=>$this->faker->dateTimeBetween($startDate,'+2 years')->format('Y-m-d'),
           
        ];
    }
}
