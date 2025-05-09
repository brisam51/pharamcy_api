<?php

namespace Database\Factories;
use App\Models\Distributor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distributor>
 */
class DistributorFactory extends Factory
{
    protected $model = Distributor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->company().'Drug distribution',
            'economic_code'=>$this->faker->unique->numerify('ECO-Code-########'),
            'contract_number'=> $this->faker->numerify('NO-CONTRCT-########'),
            'phone'=> $this->faker->phoneNumber(10),
            'address'=> $this->faker->address,
            'national_id'=> $this->faker->postcode,
            'business_type'=>$this->faker->randomElement(['Medicinal','Medicinal plants','Medical equipment','Food supplements']),
            'email'=>$this->faker->email,
            'description'=>(string) $this->faker->paragraph,
          
        ];
    }
}
