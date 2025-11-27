<?php

namespace Database\Factories;

use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'user_id' => User::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount' => $this->faker->numberBetween(50000, 1000000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'success', 'failed']),
            'reference_number' => 'REF-' . strtoupper($this->faker->unique()->bothify('???###')),
            'transaction_date' => $this->faker->dateTime(),
        ];
    }
}
