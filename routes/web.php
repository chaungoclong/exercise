<?php

namespace App;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\StatisticController;
use App\Models\User;
use Illuminate\Support\Arr;

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

// Route Components
Route::get(
    'layouts/collapsed-menu',
    [StaterkitController::class, 'collapsed_menu']
)->name('collapsed-menu');

Route::get(
    'layouts/full',
    [StaterkitController::class, 'layout_full']
)->name('layout-full');

Route::get(
    'layouts/without-menu',
    [StaterkitController::class, 'without_menu']
)->name('without-menu');

Route::get(
    'layouts/empty',
    [StaterkitController::class, 'layout_empty']
)->name('layout-empty');

Route::get(
    'layouts/blank',
    [StaterkitController::class, 'layout_blank']
)->name('layout-blank');


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
    Route::prefix('login')
        ->name('login.')
        ->group(function () {
            Route::get('', [LoginController::class, 'showFormLogin'])
                ->name('form');

            Route::post('', [LoginController::class, 'login'])
                ->name('process');
        });
    // END: Login route

    // BEGIN: Forgot password
    Route::get(
        'forgot-password',
        [ResetPasswordController::class, 'showForgotPasswordForm']
    )->name('password.request');

    Route::post(
        'forgot-password',
        [ResetPasswordController::class, 'sendEmailResetPassword']
    )->name('password.email');
    // END: Forgot password

    // BEGIN: Forgot password
    Route::get(
        'reset-password/{token}',
        [ResetPasswordController::class, 'showResetPasswordForm']
    )->name('password.reset');

    Route::post(
        'reset-password',
        [ResetPasswordController::class, 'resetPassword']
    )->name('password.update');

    // END: Forgot password
});
// END: Public route

// BEGIN: Private route

// BEGIN: Verify Email
Route::get(
    'email/verify',
    [VerifyEmailController::class, 'showVerifyEmailPage']
)->middleware('auth')
    ->name('verification.notice');

Route::get(
    '/email/verify/{id}/{hash}',
    [VerifyEmailController::class, 'verifyEmail']
)->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post(
    '/email/verification-notification',
    [VerifyEmailController::class, 'sendEmailVerificationNotification']
)->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
// END: Verify Email

// BEGIN: Logout
Route::post('logout', LogoutController::class)
    ->name('logout')
    ->middleware('auth');
// END: Logout

Route::middleware(['auth', 'userActive', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('home');
    Route::get('home', [DashboardController::class, 'index'])
        ->name('home');

    // BEGIN: Profile
    Route::controller(ProfileController::class)
        ->name('profile.')
        ->prefix('profile')
        ->group(function () {
            // Show profile
            Route::get('', 'show')->name('show');

            // Update profile
            Route::put('', 'update')->name('update');

            // Update Avatar
            Route::post('update-avatar', 'updateAvatar')
                ->name('update_avatar');
        });
    // END: Profile

    // BEGIN: Change password
    Route::patch('change-password', ChangePasswordController::class)
        ->name('change_password');
    // END: Change password

    // BEGIN: Manage Role
    Route::get(
        'roles/datatables',
        [RoleController::class, 'datatables']
    )->name('roles.datatables');

    Route::resource('roles', RoleController::class);
    // END: Manage Role


    // BEGIN: Manage Permission
    Route::get(
        'permissions/datatables',
        [PermissionController::class, 'datatables']
    )->name('permissions.datatables');

    Route::resource('permissions', PermissionController::class);
    // END: Manage Permission

    // BEGIN: Manage Positions
    Route::get(
        'positions/datatables',
        [PositionController::class, 'datatables']
    )->name('positions.datatables');

    Route::resource('positions', PositionController::class);
    // END: Manage Positions


    // BEGIN: Manage Users
    Route::get(
        'users/datatables',
        [UserController::class, 'datatables']
    )->name('users.datatables');

    Route::patch(
        'users/switch-status/{user}',
        [UserController::class, 'switchStatus']
    )->name('users.switch_status');

    Route::resource('users', UserController::class);
    // END: Manage Users

    // BEGIN: Manage Project
    Route::get(
        'projects/datatables',
        [ProjectController::class, 'datatables']
    )->name('projects.datatables');

    Route::resource('projects', ProjectController::class);
    // END: Manage Project

    // BEGIN: Manage Report
    // Datatables Report for Employee page
    Route::get(
        'reports/datatables',
        [ReportController::class, 'datatables']
    )->name('reports.datatables');

    // List report Admin page
    Route::get(
        'manager-reports',
        [ReportController::class, 'index']
    )->name('reports.index_manager');

    // Datatables Report for Admin page
    Route::get(
        'manager-reports/datatables',
        [ReportController::class, 'datatablesManager']
    )->name('reports.datatables_manager');

    // Get option when create or edit
    Route::get(
        'reports/get-select-options',
        [ReportController::class, 'getSelectOptions']
    )->name('reports.get_select_options');

    // Approve Report
    Route::patch(
        'reports/approve/{report}',
        [ReportController::class, 'approve']
    )->name('reports.approve');

    // Route Resource
    Route::resource('reports', ReportController::class);
    // END: Manage Report

    Route::prefix('statistics')
        ->name('statistics.')
        ->group(function () {
            Route::get(
                'user/{userId?}',
                [
                    StatisticController::class,
                    'getUserStatistics'
                ]
            )->name('user');

            Route::get(
                'project',
                [
                    StatisticController::class,
                    'getProjectStatistics'
                ]
            )->name('project');
        });
});
// END: Private route

Route::get('test', function () {
    dd(\Illuminate\Support\Facades\Password::RESET_LINK_SENT);
});
