<?php

namespace Database\Seeders;

use App\Models\ProductTransaction;
use Illuminate\Database\Seeder;

class ProductTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductTransaction::insert(
            [
                [
                    'id' => 1,
                    'product_id' => 1,
                    'store_id' => 1,
                    'transaction_id' => 1,
                    'total' => 100000,
                    'quantity' => 1
                ],
                [
                    'id' => 2,
                    'product_id' => 2,
                    'store_id' => 1,
                    'transaction_id' => 1,
                    'total' => 100000,
                    'quantity' => 1
                ],
                [
                    'id' => 3,
                    'product_id' => 2,
                    'store_id' => 1,
                    'transaction_id' => 2,
                    'total' => 100000,
                    'quantity' => 1
                ],
                [
                    'id' => 4,
                    'product_id' => 1,
                    'store_id' => 1,
                    'transaction_id' => 2,
                    'total' => 100000,
                    'quantity' => 1
                ]
            ]
        );
    }
}
