<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GymController;

// Đăng nhập API
Route::post('/login', [AuthController::class, 'login']);

// API công khai
Route::get('/packages', [GymController::class, 'packages']);
Route::get('/pt-packages', [GymController::class, 'ptPackages']);
Route::get('/pt/availability', [GymController::class, 'availablePt']);

// API yêu cầu đăng nhập
Route::middleware('auth:sanctum')->group(function () {

    // Tài khoản
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Đăng ký và đặt lịch PT
    Route::get('/my/pt-registrations', [GymController::class, 'myPtRegistrations']);
    Route::post('/pt/bookings', [GymController::class, 'bookPt']);
    Route::get('/my/pt-schedules', [GymController::class, 'myPtSchedules']);
    Route::delete('/my/pt-schedules/{id}', [GymController::class, 'cancelPtSchedule']);

    // Hóa đơn
    Route::get('/my/invoices', [GymController::class, 'myInvoices']);

    // Check-in tại quầy
    Route::post('/check-ins', [GymController::class, 'checkIn']);
});