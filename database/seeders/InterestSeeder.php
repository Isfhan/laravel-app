<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Interest;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            ['name' => 'Reading'],
            ['name' => 'Video Games'],
            ['name' => 'Sports'],
            ['name' => 'Traveling'],
        ];

        Interest::insert($interests);
    }
}
