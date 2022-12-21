<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::apiResource('/users', App\Http\Controllers\Api\AuthController::class);


//buat route untuk AuthController
// Route::post('/login', 'Api\AuthController@login');
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/loginAdmin', [App\Http\Controllers\Api\AuthController::class, 'loginAdmin']);

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::middleware('auth:api')->post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

Route::get('/email/verify/need-verification', [App\Http\Controllers\VerificationController::class, 'notice'])->name('verification.notice');
Route::get('/email/verify/{id}', [App\Http\Controllers\VerificationController::class, 'verify'])->name('verification.verify');

Route::group(['middleware' => 'auth:api'], function () {

    Route::apiResource('/tims', App\Http\Controllers\Api\TimController::class);
    Route::apiResource('/members', App\Http\Controllers\Api\MemberController::class);
    Route::apiResource('/jadwals', App\Http\Controllers\Api\JadwalController::class);
    Route::apiResource('/daftars', App\Http\Controllers\Api\DaftarJadwalController::class);
    Route::get('/user', [App\Http\Controllers\Api\UserController::class, 'index']);
    Route::get('/user/{id}', [App\Http\Controllers\Api\UserController::class, 'show']);
    Route::put('/user/{id}', [App\Http\Controllers\Api\UserController::class, 'update']);
    Route::delete('/user/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
});

Route::apiResource('/divisis', App\Http\Controllers\Api\DivisiController::class);
