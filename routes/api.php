<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Product\ApiProductController;
use App\Http\Controllers\Store\ApiStoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum', 'json.response']], function() {
    Route::get('/product/{id}', [ApiProductController::class, 'getAllProductbyUserId']);

    Route::get('/store/{id}', [ApiStoreController::class, 'getStoreById']);
    Route::get('/store/user/{id}', [ApiStoreController::class, 'getStoreByUserid']);
});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum', 'cors', 'json.response', 'is_admin']], function() {
    Route::get('/store/admin/{id}', [ApiStoreController::class, 'getStoreByAdminid']);
    Route::post('/store', [ApiStoreController::class, 'addStore']);
    Route::put('/store', [ApiStoreController::class, 'updateStore']);
});