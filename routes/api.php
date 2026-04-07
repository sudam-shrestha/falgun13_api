<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Course Routes
Route::get("/course/index", [CourseController::class, "index"])->middleware("auth:sanctum");
Route::post("/course/store", [CourseController::class, "store"]);
Route::patch("/course/update/{id}", [CourseController::class, "update"]);
Route::delete("/course/delete/{id}", [CourseController::class, "delete"]);

// Auth Routes
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::get("/profile", [AuthController::class, "profile"])->middleware("auth:sanctum");
Route::get("/login", [AuthController::class, "login_response"])->name("login");

Route::delete("/logout", [AuthController::class, "logout"])->middleware("auth:sanctum");
