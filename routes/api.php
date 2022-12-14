<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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


Route::post('/signup', [UserController::class, 'signUp']);
Route::post('/signin', [UserController::class, 'signIn']);

Route::middleware('auth:api')->group( function () {

    Route::post('/logout', [USerController::class, 'logout']);

    Route::get('/info', [UserController::class, 'info']);

    Route::get('/latency', [UserController::class, 'latency']);

});


