<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Sample User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Always hash your passwords!
        ]);
    }
}
