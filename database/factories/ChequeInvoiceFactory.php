<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChequeInvoice>
 */
class ChequeInvoiceFactory extends Factory
{
    protected $model = \App\Models\ChequeInvoice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cheque_id' => \App\Models\Cheque::factory(),
            'invoice_id' => \App\Models\Invoice::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 100000),
        ];
    }
}
