<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use app\Http\Controllers\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('login', 'AuthController@login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('daftar', [App\Http\Controllers\AuthController::class, 'daftar'])->name('daftar');
Route::post('forgot-password', [App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('reset-password', [App\Http\Controllers\AuthController::class, 'reset']);
// Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function() {
    // manggil controller sesuai bawaan laravel 8

    // Route::get('/', [BookController::class, 'index']);
    
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/logoutall', [App\Http\Controllers\AuthController::class, 'logoutall']);
    // manggil controller dengan mengubah namespace di RouteServiceProvider.php biar bisa kayak versi2 sebelumnya
    // Route::post('logoutall', 'AuthController@logoutall');
});

