<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert(
            [
                [
                    'id' => 1,
                    'full_name' => 'Tius',
                    'email' => 'tiusAdmin@gmail.com',
                    'username' => 'tiusAdmin',
                    'role' => 'admin',
                    'password' => Hash::make("doomhammer"),
                ],
                [
                    'id' => 2,
                    'full_name' => 'Tius',
                    'email' => 'tius@gmail.com',
                    'username' => 'tius',
                    'role' => 'cashier',
                    'password' => Hash::make("doomhammer"),
                ]
            ]
        );
        
    }
}
