<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\CustomerController;
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
        Route::get("/fullproducts", [ProductController::class, 'fullproducts']);
        Route::get("/{product}", [ProductController::class, 'show']);
        Route::post("/", [ProductController::class, 'store']);
        Route::post("/{product}", [ProductController::class, 'update']);
        Route::delete("/{product}", [ProductController::class, 'destroy']);

        Route::delete("/{product}/image", [ProductController::class, 'destroyImages']);
    });

    Route::group(["prefix" => "/customer"], function () {
        Route::get("/", [CustomerController::class, 'index']);
        Route::get("/{user}", [CustomerController::class, 'show']);
        Route::put("/{user}", [CustomerController::class, 'update']);
    });

    Route::group(["prefix" => "/customer_address"], function () {
        Route::get("/", [CustomerAddressController::class, 'index']);
        Route::get("/{user}", [CustomerAddressController::class, 'show']);
        Route::post("/{user}", [CustomerAddressController::class, 'store']);
        Route::put("/{customer_address}", [CustomerAddressController::class, 'update']);
        Route::delete("/{customer_address}", [CustomerAddressController::class, 'destroy']);
    });
});
