<?php

namespace Database\Seeders;

use App\Models\Admin;
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
                    'email' => 'tius@gmail.com',
                    'username' => 'tius',
                    'password' => Hash::make("doomhammer"),
                ],
            ]
        );

        Admin::insert(
            [
                [
                    'id' => 1,
                    'full_name' => 'TiusAdmin',
                    'email' => 'tiusAdmin@gmail.com',
                    'username' => 'tiusAdmin',
                    'password' => Hash::make("doomhammer"),
                ]
            ]
        );
        
    }
}
