<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductController extends Controller
{
    public function getAllProductbyUserId(Request $request, $userid) {
        $product = Product::where('user_id', $userid)->get();

        return response($product, 200);
    }
}
