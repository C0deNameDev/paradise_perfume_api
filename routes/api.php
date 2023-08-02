<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BottleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\UserController;
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
    Route::get('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/authenticate', 'authenticate');
    Route::post('/signUp', 'signUp');
    Route::get('/sendCofirmSignUp/{userId}', 'sendCofirmSignUp');
    Route::get('/validateSignUp/{userId}/{code}', 'validateSignUp');
    Route::get('/forgotPassword/{email}', 'forgotPassword');
    Route::get('/validateForgotPassword/{userId}/{code}', 'validateForgotPassword');
    Route::post('/resetPassword', 'resetPassword');
    Route::get('/test', 'test');
});

Route::controller(PerfumeController::class)->prefix('/perfumes')->middleware('auth:sanctum')->group(function () {
    Route::get('/page', 'paginate');
    Route::get('/', 'index');
    Route::get('/{perfume_id}', 'get_by_id');
    Route::get('/perfumePicture/{perfume_id}', 'get_perfume_picture');
    Route::get('/details/{perfume_id}', 'get_details');

});

Route::controller(UserController::class)->prefix('/user')->group(function () {
    Route::get('/userPicture/{user_id}', 'get_profile_picture')->middleware('auth:sanctum');
});

Route::controller(BottleController::class)->prefix('/bottles')->group(function () {
    Route::get('/', 'index');
    Route::get('/{order_id}', 'get_by_order');
    Route::get('/{bottle_id}', 'get_by_id');
    Route::get('/picture/{bottle_id}', 'get_bottle_picture');
});

Route::controller(OrderController::class)->prefix('/orders')->group(function () {
    Route::post('/placeOrder', 'store');
    Route::get('/delete/{order_id}', 'destroy');
    // Route::get('/client/{client_id}', 'get_client_orders');
    Route::get('/{order_id}/{bottle_id}/mark_prepared', 'mark_prepared');
    Route::get('/{order_id}/{bottle_id}/mark_pending', 'mark_pending');
    Route::get('/{order_id}/{bottle_id}/delete', 'delete_bottle_from_order');
    Route::get('/client/{user_id}', 'get_by_client');

});
