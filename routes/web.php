<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Employee Leave Management System
|--------------------------------------------------------------------------
*/

// Root & Legacy Route Redirects to Portal
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
Route::get('/home', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::fallback(function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Central Role-Based Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employee & User Leave Routes
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::post('/leaves/{leave}/cancel', [LeaveController::class, 'cancel'])->name('leaves.cancel');
    Route::get('/calendar', [LeaveController::class, 'calendar'])->name('leaves.calendar');

    // Manager Leave Approvals
    Route::get('/manager/leaves', [LeaveController::class, 'pendingApprovals'])->name('manager.leaves.index');
    Route::post('/manager/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('manager.leaves.approve');
    Route::post('/manager/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('manager.leaves.reject');

    // Notifications
    Route::post('/notifications/{id}/mark-as-read', function($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.mark-as-read');
    
    Route::post('/notifications/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.mark-all-read');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Department Management
        Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);

        // Leave Type Management
        Route::resource('leave-types', LeaveTypeController::class)->except(['create', 'show', 'edit']);
    });
});

require __DIR__.'/auth.php';
