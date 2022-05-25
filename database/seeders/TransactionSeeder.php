<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::insert(
            [
                [
                    'id' => 1,
                    'store_id' => 1,
                    'cus_id' => 1,
                    'name' => '2022-05-24 16:49',
                    'payment' => 'cash',
                    'total' => 200000
                ],
                [
                    'id' => 2,
                    'store_id' => 1,
                    'cus_id' => 1,
                    'name' => '2022-05-24 16:49',
                    'payment' => 'cash',
                    'total' => 200000
                ],
            ]
        );
    }
}
