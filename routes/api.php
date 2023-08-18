<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BottleController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Models\SuperAdmin;
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
// for Authentication
Route::controller(AuthenticationController::class)->group(function () {
    
    Route::post('/authenticate_admin', 'authenticate_admin');
    Route::get('/auth', 'getAuth')->middleware('auth:sanctum');
    Route::get('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/authenticate', 'authenticate');
    Route::get('/sendCofirmSignUp/{userId}', 'sendCofirmSignUp');
    Route::get('/validateSignUp/{userId}/{code}', 'validateSignUp');
    Route::get('/forgotPassword/{email}', 'forgotPassword');
    Route::get('/validateForgotPassword/{userId}/{code}', 'validateForgotPassword');
    Route::get('/test', 'test');
    Route::post('/signUp', 'signUp');
    Route::post('/resetPassword', 'resetPassword');
});

Route::controller(PerfumeController::class)->prefix('/perfumes')->middleware('auth:sanctum')->group(function () {
    Route::get('/page', 'paginate');
    Route::get('/', 'index');
    Route::get('/{perfume_id}', 'get_by_id');
    Route::get('/perfumePicture/{perfume_id}', 'get_perfume_picture');
    Route::get('/details/{perfume_id}', 'get_details');

});

Route::controller(UserController::class)->prefix('/user')->middleware('auth:sanctum')->group(function () {
    Route::get('/userPicture/{user_id}', 'get_profile_picture');

    Route::middleware(AdminMiddleware::class)->prefix('/admin')->group(function () {
        Route::get('/search/{query}', 'search_client');
    });
});

Route::controller(BottleController::class)->prefix('/bottles')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::get('/{order_id}', 'get_by_order');
    Route::get('/{bottle_id}', 'get_by_id');
    Route::get('/picture/{bottle_id}', 'get_bottle_picture');
});

Route::controller(OrderController::class)->prefix('/orders')->middleware('auth:sanctum')->group(function () {
    Route::middleware(AdminMiddleware::class)->prefix('/admin')->group(function () {
        Route::get('/', 'index'); //FETCH ALL ORDERS => ADMIN and SUPERADMIN
        Route::get('/prepare/bottle/{bottle_id}/order/{order_id}', 'mark_prepared'); // MARK BOTTLE AS PREPARED BY ADMIN/SUPERADMIN
        Route::get('/pending/{bottle_id}/order/{order_id}', 'mark_pending'); // MARK BOTTLE AS PENDING BY ADMIN/SUPERADMIN
        Route::get('/prepare/{order_id}', 'prepare_order');
        Route::get('/close/{order_id}', 'close_order');
    });

    Route::middleware(SuperAdminMiddleware::class)->prefix('/superAdmin')->group(function () {
        Route::get('/sales', 'get_closed_orders');
        Route::post('/sales', 'createSale');
    });

    Route::post('/placeOrder', 'store'); // PLACE A NEW ORDER BY THE CLIENT
    Route::get('/delete/{order_id}', 'destroy'); // DELETE AN ORDER BY THE CLIENT/ADMIN
    // Route::get('/client/{client_id}', 'get_client_orders');

    Route::get('/{order_id}/{bottle_id}/delete', 'delete_bottle_from_order'); // DELETE A BOTTLE FROM THE ORDER BY CLIENT
    Route::get('/client/{user_id}', 'get_by_client'); // GET ALL ORDERS OF A CLIENT

});

Route::controller(CardController::class)->middleware('auth:sanctum')->prefix('/cards')->group(function () {
    Route::post('/', 'store');
    Route::get('/auth', 'get_auth');
    Route::get('/info/{card_id}', 'get_card_info');
});
