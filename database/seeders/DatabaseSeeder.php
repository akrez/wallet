<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Currency::factory()->create([
            'key' => 'irr',
            'name' => 'irrial',
            'symbol' => 'R',
            'abbr' => 'IRR',
            'is_active' => true,
        ]);

        Currency::factory()->create([
            'key' => 'usd',
            'name' => 'dollar',
            'symbol' => '$',
            'abbr' => 'USD',
            'is_active' => true,
        ]);
    }
}
