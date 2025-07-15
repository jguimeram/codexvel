<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('movies', [MovieController::class, 'index']);
Route::post('movies', [MovieController::class, 'store']);
Route::get('movies/{movie}', [MovieController::class, 'show']);
Route::put('movies/{movie}', [MovieController::class, 'update']);
Route::delete('movies/{movie}', [MovieController::class, 'destroy']);
