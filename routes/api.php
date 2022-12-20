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
    Route::get('/users/profile', [UserController::class, 'getProfile'])
        ->middleware('can:view,user');
  
    //All users CRUD
    Route::apiResource('users', UserController::class)
        ->middleware('can:viewAdmin,user');

    //ALL orders CRUD
    Route::apiResource('orders', OrderController::class)
        ->middleware('can:viewExceptCustomer,user');

    //ALL order item CRUD
    Route::apiResource('ordersItems', OrderItemController::class)
        ->middleware('can:viewExceptCustomer,user');

    Route::get('/chefs/ordersItems/', [OrderItemController::class, 'chefIndex'])
        ->middleware('can:viewExceptCustomer,user');

    //ALL customers CRUD
    Route::apiResource('customers', CustomerController::class)
        ->middleware('can:viewExceptCustomer,user');

    //change password
    Route::put('/users/{user}/changePassword', [UserController::class, 'changePassword'])
        ->middleware('can:updatePassword,user');

    //Product
    Route::put('products/{product}', [ProductController::class, 'update'])
        ->middleware('can:viewAdmin,user');

    Route::post('products/', [ProductController::class, 'store'])
        ->middleware('can:viewAdmin,user');

    Route::get('/orders/{order}/ordersItems', [OrderController::class, 'orderItems'])
        ->middleware('can:viewExceptCustomer,user');

    Route::delete('products/{product}', [ProductController::class, 'destroy'])
        ->middleware('can:viewAdmin,user');

    Route::get('products/{product}', [ProductController::class, 'show'])
        ->middleware('can:viewAdmin,user');

});

//ALL products CRUD
Route::get('products/', [ProductController::class, 'index']);

Route::post('auth/login', [AuthenticationController::class, 'login']);
Route::post('auth/register', [AuthenticationController::class, 'register']);
