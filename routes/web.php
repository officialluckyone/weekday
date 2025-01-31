<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['auth','banned'])->group(function () {
    Route::get('/',[App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/progress-projects',[App\Http\Controllers\DashboardController::class, 'progress'])->name('dashboard.progress');

    Route::patch('/notifications/{id}/dibaca', function ($id) {
        auth()->user()->notifications()->find($id)->markAsRead();
        return redirect()->back();
    })->name('notifications.markAsRead');

    Route::patch('/notifications/dibaca-semua', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.markAllRead');

    Route::prefix('profile')->group(function () {
        Route::get('/',[App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/',[App\Http\Controllers\ProfileController::class, 'store'])->name('profile.store');
        Route::post('/upload',[App\Http\Controllers\ProfileController::class,'upload'])->name('profile.upload');
        Route::get('/settings',[App\Http\Controllers\ProfileController::class, 'setting'])->name('profile.setting');
    });

    Route::prefix('users/{users}')->group(function () {
        Route::get('/banned',[App\Http\Controllers\UserController::class, 'banned'])->name('users.banned');
        Route::get('/unbanned',[App\Http\Controllers\UserController::class, 'unbanned'])->name('users.unbanned');
    });

    Route::prefix('project/{project}/task')->group(function () {
        Route::get('/create',[App\Http\Controllers\ProjectController::class, 'add_task'])->name('project.task.create');
        Route::post('/store',[App\Http\Controllers\ProjectController::class, 'store_task'])->name('project.task.store');
        Route::prefix('{task}')->group(function () {
            Route::get('/',[App\Http\Controllers\ProjectController::class, 'show_task'])->name('project.task.show');
            Route::get('/edit',[App\Http\Controllers\ProjectController::class, 'edit_task'])->name('project.task.edit');
            Route::put('/update',[App\Http\Controllers\ProjectController::class, 'update_task'])->name('project.task.update');
            Route::delete('/destroy',[App\Http\Controllers\ProjectController::class, 'destroy_task'])->name('project.task.destroy');
        });
    });

    Route::prefix('task')->group(function () {
        Route::get('/',[App\Http\Controllers\TaskController::class,'index'])->name('task.index');
        Route::get('/{task}',[App\Http\Controllers\TaskController::class, 'show'])->name('task.show');
        Route::post('/{task}',[App\Http\Controllers\TaskController::class, 'status'])->name('task.status');
    });
    Route::get('/summary',[App\Http\Controllers\ProjectGraphicController::class, 'index'])->name('summary.index');

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('project', App\Http\Controllers\ProjectController::class);
});
