<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
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

Route::middleware('guest:sanctum')->get('/user', function (Request $request) {
    return auth()->user();
});

// // login
Route::post('login', [LoginController::class, 'login'])->name('api.login');

// logout
Route::post('logout', [LogoutController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('api.logout');
