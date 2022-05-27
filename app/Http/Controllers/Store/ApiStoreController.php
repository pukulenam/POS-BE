<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiStoreController extends Controller
{
    public function getStoreByUserid($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'integer|required|exists:users'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if (auth()->user()->id != $id && auth()->user()->role == 'user') {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }
        $store = Store::where('user_id', $id)->get();

        return response($store, 200);
    }

    public function getStoreByAdminid($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'integer|required|exists:admins'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if (auth()->user()->id != $id && auth()->user()->role == 'admin') {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }
        $store = Store::where('admin_id', $id)->get();

        return response($store, 200);
    }

    public function getStoreById($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'integer|required|exists:stores'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $store = Store::where('id', $id)->first();

        if (auth()->user()->id != $store['admin_id'] && auth()->user()->role == 'admin') {
            return response(["errors" => "You Are Not Authenticate"], 422);
        } else if (auth()->user()->id != $store['user_id'] && auth()->user()->role == 'user') {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        return response($store, 200);
    }

    public function addStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'admin_id' => 'required|integer|exists:admins,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'string|nullable',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if (Store::where('admin_id', $request['admin_id'])->first() != NULL) {
            return response(['errors' => "You Can Only Have 1 Store"], 422);
        }

        if (auth()->user()->id != $request['admin_id']) {
            return response(['errors' => "You Are Not Authenticate"], 422);
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

        $store = Store::where('id', $request['id'])->first();
        if (auth()->user()->id != $store['admin_id']) {
            return response(["errors" => "You Are Not Authenticate"], 422);
        }

        $store['name'] = $request['name'];
        $store['description'] = $request['description'];
        $store['address'] = $request['address'] ? $request['address'] : $store['address'];
        
        $store->save();

        return response($store, 200);
    }
}
