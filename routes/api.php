<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\SportController;
use App\Http\Controllers\V1\CourtController;
use App\Http\Controllers\V1\MemberController;
use App\Http\Controllers\V1\ReservationController;
use App\Http\Controllers\V1\AuthController;

/*
Route::prefix('v1')->group(function () {
    Route::apiResource('sports', SportController::class);
    Route::apiResource('courts', CourtController::class);
    Route::apiResource('members', MemberController::class);
    Route::apiResource('reservations', ReservationController::class);
    Route::apiResource('users', UserController::class);
});

*/

Route::prefix('v1')->group(function () {
    // Rutas públicas de autenticación
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Rutas protegidas con Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::match(['put', 'patch'], '/users/update', [AuthController::class, 'update']);

        // 📌 Rutas adicionales personalizadas
        Route::get('/courts/available', [CourtController::class, 'availableCourts']); // Buscar pistas disponibles
        Route::get('/reservations/day', [ReservationController::class, 'reservationsByDay']); // Listar reservas del día

        // CRUDs protegidos
        Route::apiResource('sports', SportController::class);
        Route::apiResource('courts', CourtController::class);
        Route::apiResource('members', MemberController::class);
        Route::apiResource('reservations', ReservationController::class);
    });
});
