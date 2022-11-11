<?php

use Illuminate\Http\Request;
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

//blocked by auth
Route::group(['middleware' => 'auth'], function () {
    Route::get('users/{user}', [UserController::class, 'getUser']);
    //blocked for only managers
    Route::get('users', [UserController::class, 'getUsers']);//TODO->middleware('type:EM');
});


Route::middleware(['api', 'auth.session'])->group(function () {
    route::post('users', [LoginController::class, 'login'])->name('login');
});
