<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Product\ApiProductController;
use App\Http\Controllers\ProductTransaction\ApiProductTransactionController;
use App\Http\Controllers\Store\ApiStoreController;
use App\Http\Controllers\Transaction\ApiTransactionController;
use Facade\FlareClient\Api;
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
    Route::get('/product/store/{id}', [ApiProductController::class, 'getAllProductsbyStoreId']);
    Route::get('/product/{id}', [ApiProductController::class, 'getProductbyId']);

    Route::get('/store/{id}', [ApiStoreController::class, 'getStoreById']);
    Route::get('/store/user/{id}', [ApiStoreController::class, 'getStoreByUserid']);

    Route::get('/producttransaction/store/{storeid}', [ApiProductTransactionController::class, 'getAllProductTransByStoreId']);
    Route::get('/producttransaction/transaction/{transid}', [ApiProductTransactionController::class, 'getAllProductTransByTransactionId']);

    Route::get('/transaction/store/{store_id}', [ApiTransactionController::class, 'getTransactionByStoreId']);
    Route::get('/transaction/{id}', [ApiTransactionController::class, 'getTransactionById']);
    Route::get('/transaction/customer/{cus_id}', [ApiTransactionController::class, 'getTransactionByCustomerId']);
});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/admin/login', [ApiAuthController::class, 'login_admin']);
    Route::post('/cashier/login', [ApiAuthController::class, 'login_cashier']);
});

Route::group(['middleware' => ['auth:sanctum', 'cors', 'json.response', 'is_admin']], function() {
    Route::get('/store/admin/{id}', [ApiStoreController::class, 'getStoreByAdminid']);
    Route::post('/store', [ApiStoreController::class, 'addStore']);
    Route::put('/store', [ApiStoreController::class, 'updateStore']);

    Route::post('/product', [ApiProductController::class, 'addProduct']);
    Route::delete('/product', [ApiProductController::class, 'deleteProduct']);
    Route::put('/product', [ApiProductController::class, 'updateProduct']);

    Route::post('/transaction', [ApiTransactionController::class, 'addTransaction']);
    Route::put('/transaction', [ApiTransactionController::class, 'updateTransaction']);
});