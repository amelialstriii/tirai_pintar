<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=> 'Admin',
            'email'=> 'admin@gmail.com',
            'password'=> bcrypt('12345678'),
            'email_verified_at'=> now(),
        ])->assignRole('admin');

        User::create([
            'name'=> 'Operator',
            'email'=> 'operator@gmail.com',
            'password'=> bcrypt('12345678'),
            'email_verified_at'=> now(),
        ])->assignRole('operator');

    }
}
