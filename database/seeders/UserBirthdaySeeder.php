<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserBirthdaySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'birthday' => $faker->date('Y-m-d', '2005-01-01'),
                    'start_date' => $faker->date('Y-m-d', 'now'),
                    'emergency_contact_name' => $faker->name,
                    'emergency_contact_phone' => $faker->phoneNumber,
                ]);
        }
    }
}
