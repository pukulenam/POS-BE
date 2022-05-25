<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiProductController extends Controller
{
    public function getAllProductsbyStoreId(Request $request, $store_id) {
        $store = Store::where('id', $store_id)->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $product = Product::where('store_id', $store_id)->get();

        return response($product, 200);
    }

    public function getProductbyId(Request $request, $id) {
        $product = Product::where('id', $id)->first();
        $store = Store::where('id', $product['store_id'])->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        return response($product, 200);
    }

    public function addProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|integer|exists:stores,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'image' => 'string|nullable'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = Store::where('id', $request['store_id'])->first();

        if (auth()->user()->id != $store['admin_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        if ($request['quantity'] < 0 || $request['price'] < 0) {
            return response(["errors" => "Quantity or Price must be Positive"], 422);
        }

        $product = Product::create($request->toArray());

        return response($product, 200);
    }

    public function updateProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'image' => 'string|nullable'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $product = Product::where('id', $request['id'])->first();
        
        $store = Store::where('id', $product['store_id'])->first();
        
        if (auth()->user()->id != $store['admin_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $product['name'] = $request['name'];
        $product['description'] = $request['description'];
        $product['quantity'] = $request['quantity'];
        $product['price'] = $request['price'];
        $product['category'] = $request['category'];
        $product['image'] = $request['image'] ? $request['image'] : NULL;

        $product->save();

        return response($product, 200);
    }

    public function deleteProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $product = Product::where('id', $request['id'])->first();

        $store = Store::where('id', $product['store_id'])->first();

        if (auth()->user()->id != $store['admin_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $product->delete();

        return response(['Product' => $product, "msg" => "deleted"], 200);
    }
}
