<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LeaveApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes (Module 14 Requirements)
|--------------------------------------------------------------------------
*/

// Public Authentication API
Route::post('/login', [AuthApiController::class, 'login']);

// Authenticated Sanctum API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/profile', [AuthApiController::class, 'profile']);

    // Leave Types & Balance
    Route::get('/leave-types', [LeaveApiController::class, 'leaveTypes']);
    Route::get('/leave-balance', [LeaveApiController::class, 'leaveBalance']);

    // Leaves Management
    Route::get('/leaves', [LeaveApiController::class, 'index']);
    Route::post('/leaves', [LeaveApiController::class, 'store']);
    Route::get('/leaves/{id}', [LeaveApiController::class, 'show']);
    Route::post('/leaves/{id}/cancel', [LeaveApiController::class, 'cancel']);

    // Manager / Approver API Routes
    Route::get('/leave-requests', [LeaveApiController::class, 'managerRequests']);
    Route::post('/leave-requests/{id}/approve', [LeaveApiController::class, 'approve']);
    Route::post('/leave-requests/{id}/reject', [LeaveApiController::class, 'reject']);
});
