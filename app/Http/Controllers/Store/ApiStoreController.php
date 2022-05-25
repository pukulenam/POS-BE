<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiStoreController extends Controller
{
    public function getStoreByUserid($id) {
        if (auth()->user()->id != $id) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }
        $store = Store::where('user_id', $id)->get();

        return response($store, 200);
    }

    public function getStoreByAdminid($id) {
        if (auth()->user()->id != $id) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }
        $store = Store::where('admin_id', $id)->get();

        return response($store, 200);
    }

    public function getStoreById($id) {
        $store = Store::where('id', $id)->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->id != $store['user_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        return response($store, 200);
    }

    public function addStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'admin_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'string|nullable',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if (auth()->user()->role != 'admin') {
            return response(['errors' => "You Are Not Authenticate"], 422);
        }

        if (Store::where('admin_id', $request['admin_id'])->first() != NULL) {
            return response(['errors' => "You Can Only Have 1 Store"], 422);
        }

        $store = Store::create($request->toArray());

        return response($store, 200);
    }

    public function updateStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:stores',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'string|nullable',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if (auth()->user()->role != 'admin') {
            return response(['errors' => "You Are Not Authenticate"], 422);
        }

        $store = Store::where('id', $request['id'])->first();
        $store['name'] = $request['name'];
        $store['description'] = $request['description'];
        $store['address'] = $request['address'] ? $request['address'] : $store['address'];
        
        $store->save();

        return response($store, 200);
    }
}
