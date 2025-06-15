<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an Admin User
        User::create([
            'name' => 'Joel',
            'email' => 'joel123@gmail.com',
            'password' => Hash::make('password123'), // Password is 'password'
            'role' => 'admin',
        ]);

     
    }
}
