<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\DriverController;

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
    Route::post("auth/logout",[AuthenticationController::class,'logout']);
    //Profile routes
    Route::get('/users/profile', [UserController::class,'getProfile']);
    Route::put('/users/profile', [UserController::class,'editProfile']);
    //Confirm order delivered
    Route::put('/orders/{order}/confirm', [OrderController::class,'confirmOrder']);
    //Avalivable order to drivers
    Route::get('/drivers/orders', [OrderController::class,'indexDelivery']);
    //orders by driver
    Route::get('/orders/driver/{driver}', [OrderController::class,'ordersByDriver']);
    //Active orders 
    Route::get('/users/orders', [UserController::class,'getActiveOrders']);
    //All users CRUD
    Route::apiResource('users', UserController::class);
    //ALL orders CRUD
    Route::apiResource('orders',OrderController::class);
    //ALL customers CRUD
    Route::apiResource('customers',CustomerController::class);
    //ALL drivers CRUD
    Route::apiResource('drivers',DriverController::class);

    //change password
    Route::put('/users/{user}/changePassword', [UserController::class,'changePassword']);

});

Route::post('auth/login', [AuthenticationController::class, 'login']);
Route::post('auth/register', [AuthenticationController::class, 'register']);

