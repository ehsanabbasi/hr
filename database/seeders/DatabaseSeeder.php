<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            DepartmentSeeder::class,
            JobTitleSeeder::class,
            UserSeeder::class,
            DocumentTypeSeeder::class,
            UserBirthdaySeeder::class,
            NotificationSeeder::class,
            RemainingPermissionsSeeder::class,
        ]);
    }
}
