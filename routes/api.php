<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::post("/auth/login", [AuthController::class, "login"])->name("login");
Route::post("/auth/logout", [AuthController::class, "logout"])->middleware('auth:sanctum');
Route::apiResource("/users", UsersController::class);
Route::apiResource("/expenses", ExpensesController::class)->middleware('auth:sanctum');