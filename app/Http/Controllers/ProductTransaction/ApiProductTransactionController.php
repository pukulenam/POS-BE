<?php

namespace App\Http\Controllers\ProductTransaction;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiProductTransactionController extends Controller
{
    public function getAllProductTransByStoreId(Request $request, $storeid) {
        $validator = Validator::make(['store_id' => $storeid], [
            'store_id' => 'exists:stores,id'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = Store::where('id', $storeid)->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $PT = DB::table('product_transactions')
                ->leftJoin('products', 'product_transactions.product_id', '=', 'products.id')
                ->select('products.*', 'product_transactions.*', 'products.id as p_id', 'product_transactions.id as id')
                ->where('product_transactions.store_id', $storeid)
                ->orderBy('product_transactions.id', 'DESC')
                ->get();

        return response($PT, 200);
    }

    public function getAllProductTransByTransactionId(Request $request, $transid) {
        $validator = Validator::make(['transaction_id' => $transid], [
            'transaction_id' => 'exists:transactions,id'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = DB::table('transactions')
                    ->join('stores', 'transactions.store_id', '=', 'stores.id')
                    ->where('transactions.id', $transid)
                    ->get();

        $store = json_decode(json_encode($store[0]), true);
        
        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $PT = DB::table('product_transactions')
                ->where('transaction_id', $transid)
                ->get();

        return response($PT, 200);
    }

    public function getAllProductTransByProductId(Request $request, $productid) {
        $validator = Validator::make(['transaction_id' => $productid], [
            'transaction_id' => 'exists:transactions,id'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = DB::table('stores')
                    ->join('products', 'proudcts.store_id', '=', 'stores.id')
                    ->where('products.id', $productid)
                    ->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $PT = DB::table('product_transactions')
                ->join('products', 'product_transactions.product_id', '=', 'products.id')
                ->where('product_transactions.product_id', $productid)
                ->get();

        return response($PT, 200);
    }

    public function addProductTransaction($obj, $trans) {
        foreach ($obj as $o) {
            $product = Product::where('id', $o['id'])->first();

            if (!$product) 
                return ['errors' => "item {$o['id']} not found"];
        
            ProductTransaction::insert([
                'product_id' => $o['id'],
                'store_id' => $trans['store_id'],
                'transaction_id' => $trans['id'],
                'total' => $o['quantity'] * $product['price'],
                'quantity' => $o['quantity']
            ]);

            $product['quantity'] -= $o['quantity'];
            $product->save();
        }

        return ['errors' => null, 'status' => 'success'];
    }

    public function updateProductTransaction($obj, $trans) {
        $PT = DB::table('product_transactions')
                ->join('products', 'product_transactions.product_id', '=', 'products.id')
                ->select('product_transactions.*', 'products.price')
                ->where('product_transactions.transaction_id', $trans['id'])
                ->get();

        $PT = json_decode(json_encode($PT), true);

        foreach ($PT as $p) {
            $event = false;
            foreach ($obj as $i => $o) {
                $product = Product::where('id', $o['id'])->first();
                if ($p['product_id'] == $o['id'] && $p['deleted_at'] == NULL) {
                    if ($o['quantity'] == 0) {

                        $product['quantity'] += $p['quantity'];
                        $product->Save();

                        $p['quantity'] = 0;
                        ProductTransaction::where('product_id', $p['product_id'])->delete();
                    } else {
                        ProductTransaction::where('id', $p['id'])->update(
                            [
                                'quantity' => $o['quantity'],
                                'total' => $o['quantity'] * $p['price']
                            ]
                        );
                        $product['quantity'] -= $o['quantity'] - $p['quantity'];
                        $product->Save();
                    }

                    $event = true;

                    unset($obj[$i]);
                }
            }
            if (!$event) {
                $tmp = ProductTransaction::where('id', $p['id'])->first();
                if ($tmp) {
                    $product['quantity'] += $tmp['quantity'];
                    $tmp->update(['quantity' => 0, 'total' => 0]);
                    $tmp->delete();
                }
            }
        }

        foreach ($obj as $o) {
            $product = Product::where('id', $o['id'])->first();

            if (!$product) 
                return ['errors' => "item {$o['id']} not found"];

            ProductTransaction::insert([
                'product_id' => $o['id'],
                'store_id' => $trans['store_id'],
                'transaction_id' => $trans['id'],
                'total' => $o['quantity'] * $product['price'],
                'quantity' => $o['quantity']
            ]);

            $product['quantity'] -= $o['quantity'];
            $product->save();
        }

        return ['errors' => null, 'status' => 'success'];
    }
}
