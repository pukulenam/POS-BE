<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProductTransaction\ApiProductTransactionController;
use Illuminate\Support\Facades\DB;

class ApiTransactionController extends Controller
{
    public function getTransactionById(Request $request, $id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'exists:transactions'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $trans = Transaction::where('id', $id)->first();

        $store = Store::where('id', $trans['store_id'])->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        return response($trans, 200);
    }

    public function getTransactionByStoreId(Request $request, $store_id) {
        $validator = Validator::make(['id' => $store_id], [
            'id' => 'exists:stores'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = Store::where('id', $store_id)->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $trans = Transaction::where('store_id', $store_id)->get();

        return response($trans, 200);
    }

    public function getTransactionByCustomerId(Request $request, $cus_id) {
        $validator = Validator::make(['id' => $cus_id], [
            'id' => 'exists:customers'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $trans = Transaction::where('cus_id', $cus_id)->get();
        
        $store = Store::where('id', $trans[0]['store_id'])->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        return response($trans, 200);
    }

    public function addTransaction(Request $request) {
        $validator = Validator::make($request->toArray(), [
            'store_id' => 'required|integer|exists:stores,id',
            'cus_id' => 'required|integer|exists:customers,id',
            'name' => 'string|max:255',
            'payment' => 'in:ovo,cash,mbank',
            'total' => 'required|numeric',
            'product' => 'required|array'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = Store::where('id', $request['store_id'])->first();

        if (auth()->user()->id != $store['admin_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $transaction['store_id'] = $request['store_id'];
        $transaction['cus_id'] = $request['cus_id'];
        $transaction['name'] = $request['name'] ? $request['name'] : NULL;
        $transaction['payment'] = $request['payment'];
        $transaction['total'] = $request['total'];

        $trans = Transaction::create($transaction);

        $product_controller = (new ApiProductTransactionController);

        $res = $product_controller->addProductTransaction($request['product'], $trans);
        
        if ($res['errors']) 
            return response(['errors' => $res['errors']], 422);

        return response(['transaction' => $trans, 'product transactions' => $res['status']], 200);
    }

    public function updateTransaction(Request $request) {
        $validator = Validator::make($request->toArray(), [
            'id' => 'required|integer|exists:transactions',
            'store_id' => 'required|integer|exists:stores,id',
            'cus_id' => 'required|integer|exists:customers,id',
            'name' => 'string|max:255',
            'payment' => 'in:ovo,cash,mbank',
            'total' => 'required|numeric',
            'product' => 'required|array'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = Store::where('id', $request['store_id'])->first();

        if (auth()->user()->id != $store['admin_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $transaction = Transaction::where('id', $request['id'])->first();
        $transaction['name'] = $request['name'] ? $request['name'] : NULL;
        $transaction['payment'] = $request['payment'] ? $request['payment'] : NULL;
        $transaction['total'] = $request['total'] ? $request['total'] : NULL;

        $product_controller = (new ApiProductTransactionController);

        $res = $product_controller->updateProductTransaction($request['product'], $transaction);

        if ($res['errors']) 
            return response(['errors' => $res['errors']], 422);
        
        $transaction->save();
        return response(['transaction' => $transaction, 'product transactions' => $res['status']], 200);
    }
}
