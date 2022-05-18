<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails())
            return response(['errors' => $validator->errors()->all()], 422);

        $user = User::where('email', $request['email'])->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $response['user'] = $user;
                $response['token'] = $user->createToken('Token')->plainTextToken;
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }
}
