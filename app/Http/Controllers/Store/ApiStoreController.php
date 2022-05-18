<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiStoreController extends Controller
{
    public function getStoreByUserid($id) {
        $store = Store::where('id_user', $id)->get();

        return response($store, 200);
    }

    public function getStoreByAdminid($id) {
        $store = Store::where('id_admin', $id)->get();

        return response($store, 200);
    }

    public function getStoreById($id) {
        $store = Store::where('id', $id)->get();

        return response($store, 200);
    }

    public function addStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|integer|exists:users,id',
            'id_admin' => 'required|integer|exists:users,id',
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

        if (Store::where('id_admin', $request['id_admin'])->first() != NULL) {
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
