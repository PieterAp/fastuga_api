<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\OrderItemController;

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

Route::middleware('auth:api')->group(function () {
    Route::post("auth/logout", [AuthenticationController::class, 'logout']);

    //Profile routes
    Route::get('/users/profile', [UserController::class, 'getProfile']);
    Route::put('/users/profile', [UserController::class, 'editProfile']);

    //All users CRUD
    Route::apiResource('users', UserController::class);
    //ALL orders CRUD
    Route::apiResource('orders', OrderController::class);
    //ALL order item CRUD
    Route::apiResource('ordersItems', OrderItemController::class);
    Route::get('/chefs/ordersItems/', [OrderItemController::class, 'chefIndex']);
    //ALL customers CRUD
    Route::apiResource('customers', CustomerController::class);
    //change password
    Route::put('/users/{user}/changePassword', [UserController::class, 'changePassword']);

    //Product
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::post('products/', [ProductController::class, 'store']);
});

 //ALL products CRUD
 Route::apiResource('products', ProductController::class);

Route::post('auth/login', [AuthenticationController::class, 'login']);
Route::post('auth/register', [AuthenticationController::class, 'register']);
