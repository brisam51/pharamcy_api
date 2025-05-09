<?php

namespace Database\Factories;

use App\Models\Distributor;
use App\Models\Invoice;
use App\Models\Pharamcy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deliveryDate=$this->faker->dateTimeBetween('-1month','+1month');
        $dueDate=$this->faker->dateTimeBetween($deliveryDate,'+1month');
        return [
            'pharamcy_id'=>Pharamcy::factory(),
            'distributor_id'=>Distributor::factory(),
            'user_id'=>\App\Models\User::factory(),
            'invoice_number'=>$this->faker->unique->numerify('INV:########'),//numerify('NATION-ID-########')
            "delivery_date"=>$deliveryDate->format('Y-m-d'),
            'total_amount'=>$this->faker->randomFloat(2,100,100000),
            'paid_amount'=>$this->faker->randomFloat(2,100,100000),
           'outstanding_amount'=>$this->faker->randomFloat(2,100,100000),
            'due_date'=> $dueDate->format('Y-m-d'),
            'description'=>$this->faker->paragraph,
            'photo'=>$this->faker->imageUrl(200, 200, 'business', true, 'photo'),
            'status'=>$this->faker->randomElement(['paid','open','due','unmatured']),

        ];
    }
}
