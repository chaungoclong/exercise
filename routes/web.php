<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\StaterkitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [StaterkitController::class, 'home'])->name('home');
Route::get('home', [StaterkitController::class, 'home'])->name('home')->middleware('auth');
// Route Components
Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
Route::get('layouts/full', [StaterkitController::class, 'layout_full'])->name('layout-full');
Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');


// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// BEGIN: Public route
Route::middleware(['guest'])->group(function () {
    // BEGIN: Register route
    Route::prefix('register')->name('register.')->group(function () {
        // form register
        Route::get('', [RegisterController::class, 'showFormRegister'])
            ->name('form');

        // Register process
        Route::post('', [RegisterController::class, 'register'])
            ->name('process');
    });
    // END: Register route
    
    // BEGIN: Login route
    Route::prefix('login')->name('login.')->group(function () {
        Route::get('', [LoginController::class, 'showFormLogin'])->name('form');
        Route::post('', [LoginController::class, 'login'])->name('process');
    });
    // END: Login route
});
// END: Public route

// BEGIN: Private route
Route::middleware(['auth'])->group(function () {
    // BEGIN: Logout
    Route::post('logout', LogoutController::class)->name('logout');
    // END: Logout
});
// END: Private route

Route::get('test', function (Illuminate\Http\Request $request) {
    dd(\Gate::abilities());
})->name('register.form');
