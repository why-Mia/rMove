<?php

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

Route::get('/', function () {
    return view('welcome');
});


 
Route::get('/user/{id}', [\App\Http\Controllers\UserController::class, 'show']);

Route::get('/map/{id}', [\App\Http\Controllers\MapsController::class, 'show']);

Route::get('/maps/refresh', [\App\Http\Controllers\MapsController::class, 'refresh']);

Route::get('/times/refresh', [\App\Http\Controllers\TimesController::class, 'refresh']);

Route::group(['middleware' => 'auth'],function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::view(uri:'settings', view:'settings')->name(name:'settings');
    Route::put(uri:'settings', action:[\App\Http\Controllers\SettingsController::class, 'update'])
    ->name(name:'settings.update');
    Route::put(uri:'settings/check-username', action:[\App\Http\Controllers\SettingsController::class, 'check_username'])
    ->name(name:'settings.check_username');
    Route::put(uri:'settings/refresh-code', action:[\App\Http\Controllers\SettingsController::class, 'refresh_verification_code'])
    ->name(name:'settings.refresh_code');
    Route::put(uri:'settings/verify-account', action:[\App\Http\Controllers\SettingsController::class, 'verify_account'])
    ->name(name:'settings.verify_account');
    Route::put(uri:'settings/unlink-account', action:[\App\Http\Controllers\SettingsController::class, 'unlink_account'])
    ->name(name:'settings.unlink_account');
});

require __DIR__.'/auth.php';
