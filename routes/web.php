<?php

use Illuminate\Support\Facades\Route;

// Redirect ke daftar proyek atau halaman utama
Route::get('/', function () {
    return redirect()->route('projects.index');
});

// Include rute autentikasi
require __DIR__.'/auth.php';

// Group routes yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Route dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route resource untuk proyek
    Route::resource('projects', App\Http\Controllers\ProjectController::class);

    // Nested routes untuk tugas
    Route::prefix('projects/{project}')->group(function () {
        Route::get('tasks/create', [App\Http\Controllers\TaskController::class, 'create'])->name('tasks.create');
        Route::post('tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
        Route::get('tasks/{task}/edit', [App\Http\Controllers\TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
    });
});