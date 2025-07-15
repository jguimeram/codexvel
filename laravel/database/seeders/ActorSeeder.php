<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Actor;

class ActorSeeder extends Seeder
{
    public function run(): void
    {
        $actors = [
            'Robert Downey Jr.',
            'Scarlett Johansson',
            'Chris Evans',
            'Tom Holland',
        ];

        foreach ($actors as $name) {
            Actor::firstOrCreate(['name' => $name]);
        }
    }
}
