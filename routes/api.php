<?php

use App\Http\Controllers\ItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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


Route::post('/register', [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);