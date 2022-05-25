<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiCustomerController extends Controller
{
    public function getAllCustomerByStoreId(Request $request, $storeid) {
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

        $customer = Customer::where('store_id', $storeid)->get();

        return response($customer, 200);
    }

    public function getOneCustomerById(Request $request, $id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'exists:customers'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $customer = Customer::where('id', $id)->first();

        return response($customer, 200);
    }
}
