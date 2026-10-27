<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['name' => 'Jane Tester', 'email' => 'jane@lab.test', 'password' => 'password']);
        $statuses = ['new', 'processing', 'shipped', 'delivered', 'cancelled'];
        foreach (range(1, 40) as $i) {
            Order::create([
                'number' => 'ORD-'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer' => fake()->name(),
                'status' => $statuses[$i % 5],
                'total' => fake()->randomFloat(2, 20, 900),
                'paid' => $i % 3 !== 0,
                'notes' => fake()->sentence(),
                'created_at' => now()->subDays(40 - $i),
            ]);
        }
    }
}
