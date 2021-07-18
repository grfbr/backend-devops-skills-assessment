<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

Route::post('login', [AuthController::class, 'authenticate']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:api']], function() {
    // USER Routes
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    //ITEMS Routes
    Route::get('items', [ItemController::class, 'index']);
    Route::get('items/{id}', [ItemController::class, 'show']);
    Route::post('items', [ItemController::class, 'store']);
    Route::put('items/{id}',  [ItemController::class, 'update']);
    Route::put('items/quantity/{id}',  [ItemController::class, 'updateQuantity']);
    Route::delete('items/{item}',  [ItemController::class, 'destroy']);
});
