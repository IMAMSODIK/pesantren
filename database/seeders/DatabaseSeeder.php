<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => bcrypt('123'),
            'role'     => 'admin'
        ]);

        User::create([
            'name'     => 'Operator 1',
            'email'    => 'op1@gmail.com',
            'password' => bcrypt('123'),
            'role'     => 'operator'
        ]);

        User::create([
            'name'     => 'Operator 2',
            'email'    => 'op2@gmail.com',
            'password' => bcrypt('123'),
            'role'     => 'operator'
        ]);
    }
}
