<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresencesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LeaveRequestController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Akses khusus untuk HR Manager
Route::middleware(['role:HR'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/departments', DepartmentController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/employees', EmployeeController::class);
});

// Akses umum keperluan sistem oleh semua role
Route::middleware(['role:HR,Developer,Sales,Finance'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/presences', PresencesController::class);
    Route::resource('/payrolls', PayrollController::class);
    Route::resource('/leave-requests', LeaveRequestController::class);
    Route::resource('/tasks', TaskController::class);

    Route::get('/tasks/done/{id}', [TaskController::class, 'done'])->name('tasks.done');
    Route::get('/tasks/pending/{id}', [TaskController::class, 'pending'])->name('tasks.pending');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
