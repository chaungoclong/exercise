<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\StaterkitController;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\User\EloquentUserRepository;
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
Route::get('home', [StaterkitController::class, 'home'])->name('home')->middleware('auth:api');
// Route Components
Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
Route::get('layouts/full', [StaterkitController::class, 'layout_full'])->name('layout-full');
Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');


// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::get('test', function (UserRepository $user) {
    return view('pages.auth.register');
})->name('test');

// BEGIN: Public route
Route::middleware(['guest'])->prefix('register')->group(function () {
    // BEGIN: Register route
    Route::name('register.')->group(function () {
        // form register
        Route::get('', [RegisterController::class, 'showFormRegister'])
            ->name('form');

        // Register account detail
        Route::post(
            'account-details',
            [RegisterController::class, 'registerAccountDetails']
        )->name('account_details');

        // Register personal info
        Route::post(
            'personal-info',
            [RegisterController::class, 'registerPersonalInfo']
        )->name('personal_info');
    });
    // END: Register route
});
// END: Public route
