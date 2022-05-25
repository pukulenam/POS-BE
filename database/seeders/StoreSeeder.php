<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::insert([
            'id' => 1,
            'user_id' => 1,
            'admin_id' => 1,
            'name' => 'tokobagus',
            'description' => 'Toko bagus sekarang jual mobil',
            'address' => 'Jln. Bagus Jual Mobil, Jakarta'
        ]);
    }
}
