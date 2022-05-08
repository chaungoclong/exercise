<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positionSeeds = config('seeder.position', []);

        foreach ($positionSeeds as $positionSeed) {
            Position::firstOrCreate($positionSeed);
        }
    }
}
