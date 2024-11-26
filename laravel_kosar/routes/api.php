<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BasketController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('baskets', [BasketController::class, 'index']);
Route::get('baskets/{user_id}/{item_id}', [BasketController::class, 'show']);
Route::post('baskets', [BasketController::class, 'store']);

//Dolgozat
//admin
Route::post('admin/login', [AdminAuthController::class, 'login']);
//a
Route::get('/user/basket', [BasketController::class, 'showBasketWithUserData']);
//b
Route::get('/user/{user_id}/basket/{year}', [BasketController::class, 'showBasketForYear']);
