<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/auth/login", [AuthController::class, "login"])->name("login");
Route::post("/auth/logout", [AuthController::class, "logout"])->middleware('auth:sanctum');
Route::resource("/users", UsersController::class);
Route::resource("/expenses", ExpensesController::class)->middleware('auth:sanctum');