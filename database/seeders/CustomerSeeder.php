<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::insert([
            'id' => 1,
            'store_id' => 1,
            'full_name' => "ahmad dolin",
            'no_telp' => "087575487421",
        ]);
    }
}
