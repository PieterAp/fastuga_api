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

    Route::get('/users/profile', [UserController::class, 'getProfile']);
    Route::post('auth/logout', [AuthenticationController::class, 'logout']);
    Route::put('/users/{user}/changePassword', [UserController::class, 'changePassword']);
    Route::post('ordersItems', [OrderItemController::class,'store']);
   
    Route::middleware('managerSelf')->group(function () {
        Route::get('/users/{user}', [UserController::class, 'show']);
    });

    Route::middleware('notCustomer')->group(function () {

        Route::apiResource('orders', OrderController::class);
        Route::apiResource('ordersItems', OrderItemController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::get('/chefs/ordersItems/', [OrderItemController::class, 'chefIndex']);
        Route::get('/orders/{order}/ordersItems', [OrderController::class, 'orderItems']);

    });
 
    Route::middleware('manager')->group(function () {

        Route::apiResource('users', UserController::class);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::post('products/', [ProductController::class, 'store']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);
        Route::get('products/{product}', [ProductController::class, 'show']);

    });

});

Route::post('orders', [OrderController::class, 'store']);
Route::get('products/', [ProductController::class, 'index']);
Route::post('auth/login', [AuthenticationController::class, 'login']);
Route::post('auth/register', [AuthenticationController::class, 'register']);
