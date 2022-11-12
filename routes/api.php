<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\LoginController;


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

Route::middleware('auth')->group(function () {
    Route::middleware('manager')->group(function () {
        // All manager routes go here.
        Route::get('users', [UserController::class, 'getUsers'])->name('users');
    });
});

Route::middleware(['api', 'auth.session'])->group(function () {
    route::post('auth/login', [LoginController::class, 'authenticate'])->name('login');
    route::post('auth/logout', [LoginController::class, 'logout'])->name('logout');
});
