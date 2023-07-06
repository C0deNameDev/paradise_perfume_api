<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/auth', 'getAuth')->middleware('auth:sanctum');
    Route::post('/authenticate', 'authenticate');
    Route::get('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/signUp', 'signUp');
    Route::get('/validateSignUp/{userId}/{code}', 'validateSignUp');
    Route::get('/test', 'test');
});
