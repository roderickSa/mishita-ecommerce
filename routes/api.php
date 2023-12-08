<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProductController;
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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(["prefix" => "/auth"], function () {
    Route::post("/register", [UserAuthController::class, 'register'])->name("user.register");
    Route::post("/login", [UserAuthController::class, 'login'])->name("user.login");

    Route::group(["middleware" => "auth:api"], function () {
        Route::get("/logout", [UserAuthController::class, 'logout'])->name("user.logout");
    });
});

Route::group(["middleware" => "auth:api"], function () {
    Route::apiResource("/user", UserController::class);

    Route::apiResource("/category", CategoryController::class);

    Route::apiResource("/district", DistrictController::class);

    Route::group(["prefix" => "/product"], function () {
        Route::get("/", [ProductController::class, 'index']);
        Route::post("/", [ProductController::class, 'store']);
        Route::get("/{product}", [ProductController::class, 'show']);
        Route::post("/{product}", [ProductController::class, 'update']);
        Route::delete("/{product}", [ProductController::class, 'destroy']);

        Route::delete("/{product}/image", [ProductController::class, 'destroyImages']);
    });
});
