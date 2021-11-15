<?php

use App\Http\Controllers\ItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffersController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/items', [ItemsController::class, 'index']);
Route::post('/items', [ItemsController::class, 'store']);

Route::get("/items/{id}", [ItemsController::class, 'show']);
Route::delete("/items/{id}", [ItemsController::class, 'destroy']);

Route::get('/user-offers', [OffersController::class, 'getUserOffers']);
Route::get('/buy-items', [ItemsController::class, 'getUserBuyItems']);
Route::get('/sold-items', [ItemsController::class, 'getUserSoldItems']);

Route::group([

    'middleware' => 'api',

], function ($router) {
    Route::post("/login", [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/me', [AuthController::class, 'me']);
});


Route::post("/refresh-token", [AuthController::class, 'refreshToken']);
Route::post("/logout", [AuthController::class, 'logout']);
Route::put("/update", [AuthController::class, 'update']);

Route::post("/offers", [OffersController::class, 'store']);
Route::delete("/offers/{id}", [OffersController::class, 'destroy']);