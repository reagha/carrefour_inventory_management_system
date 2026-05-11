<?php

namespace Database\Factories;

use App\Models\BranchRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchRequestItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_request_id' => BranchRequest::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
