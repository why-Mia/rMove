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



Route::group(['middleware' => 'auth'],function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::view(uri:'settings', view:'settings')->name(name:'settings');
    Route::put(uri:'settings', action:[\App\Http\Controllers\SettingsController::class, 'update'])
    ->name(name:'settings.update');
});

require __DIR__.'/auth.php';
