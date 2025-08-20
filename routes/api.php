<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('products/best-sellers', [ProductController::class, 'bestSellers']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/category/{category}', [ProductController::class, 'byCategory']);

Route::get('/products/{id}', [ProductController::class, 'show']);


Route::prefix('auth')->group(function () {
    // 🔹 Rutas públicas
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    

    // 🔹 Rutas protegidas por JWT
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);

        // 🔹 Ejemplo: solo superadmin
        Route::middleware('role:superadmin')->group(function () {
            Route::get('superadmin/dashboard', fn () => response()->json([
                'message' => 'Bienvenido Superadmin 🚀'
            ]));
        });

        // 🔹 Ejemplo: solo company_admin
        Route::middleware('role:company_admin')->group(function () {
            Route::get('company/dashboard', fn () => response()->json([
                'message' => 'Panel de la empresa ✅'
            ]));
        });
    });
});
