<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert(
            [
                [
                    'id' => 1,
                    'store_id' => 1,
                    'name' => 'bangku taman',
                    'description' => 'bangku taman ini sanggup nahan big show',
                    'quantity' => 100,
                    'price' => 100000,
                    'category' => 'tempat duduk',
                    'image' => 'https://fatmakaryaindah.co.id/wp-content/uploads/2015/01/pentingnya-kursi-taman-1.jpg',
                ],
                [
                    'id' => 2,
                    'store_id' => 1,
                    'name' => 'Sepatu Roda',
                    'description' => 'bangku taman ini sanggup nahan big show',
                    'quantity' => 100,
                    'price' => 100000,
                    'category' => 'tempat duduk',
                    'image' => 'https://fatmakaryaindah.co.id/wp-content/uploads/2015/01/pentingnya-kursi-taman-1.jpg',
                ]
            ]
        );
    }
}
