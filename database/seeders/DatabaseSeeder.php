<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 他の Seeder もここで呼べる
        $this->call([
            CustomerSeeder::class,
            ReservationSeeder::class,
            // UserSeeder::class,
        ]);
    }
}
