<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'branch_manager_id' => User::factory(),
            'status' => 'pending',
            'dispatched_by' => null,
            'dispatched_at' => null,
        ];
    }
}
