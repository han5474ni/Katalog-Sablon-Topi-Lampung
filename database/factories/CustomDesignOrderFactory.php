<?php

namespace Database\Factories;

use App\Models\CustomDesignOrder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomDesignOrderFactory extends Factory
{
    protected $model = CustomDesignOrder::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'design_description' => $this->faker->paragraph(),
            'quantity' => $this->faker->numberBetween(1, 50),
            'total_price' => $this->faker->numberBetween(100000, 1000000),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
            'admin_notes' => $this->faker->optional()->sentence(),
        ];
    }
}
