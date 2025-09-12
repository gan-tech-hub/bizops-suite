<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory()->count(5)->create();

        // または固定データで入れるなら：
        // Customer::create([
        //     'name' => '田中太郎',
        //     'email' => 'tanaka@example.com',
        //     'phone' => '090-1234-5678',
        // ]);
    }
}