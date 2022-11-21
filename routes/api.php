<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\OrderController;

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
    //ONLY DRIVERS
    Route::get('users/drivers',[UserController::class,'getDrivers']);
    //ONLY CUSTOMERS
    //Route::get('users/customers',[UserController::class,'getCustomers']);
    //All users CRUD
    Route::apiResource('users', UserController::class);
    //ALL orders CRUD
    Route::apiResource('orders',OrderController::class);
    //ALL customers CRUD
    Route::apiResource('customers',CustomerController::class);
});

Route::post('auth/login', [AuthenticationController::class, 'login']);
Route::post('auth/register', [AuthenticationController::class, 'register']);

