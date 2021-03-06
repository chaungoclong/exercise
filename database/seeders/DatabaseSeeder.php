<?php

namespace Database\Seeders;

use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            PositionSeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
            ProjectMemberSeeder::class,
            ReportSeeder::class
        ]);
    }
}
