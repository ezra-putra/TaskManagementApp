<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TaskController;

Route::get('/login', function () {
    return view('authentication.login');
});
Route::get('/register', function () {
    return view('authentication.register');
});
Route::post('/register', [RegisterController::class, 'register'])->name('register.user');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [MainController::class, 'index']);

Route::middleware(['auth'])->group(function() {
    Route::get('/search', [MainController::class, 'search']);
    Route::get('/search-owner', [MainController::class, 'searchByOwner']);

    Route::post('/add-task', [TaskController::class, 'createTask'])->name('task.post');
    Route::delete('/delete-task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');
    Route::get('/list-task', [TaskController::class, 'listTask']);
    Route::get('/assign-task/{id}', [TaskController::class, 'AssignTaskForm']);
    Route::post('/add-assign-task', [TaskController::class, 'assignTask'])->name('assign.post');
    Route::delete('/delete-user-assign/{id}', [TaskController::class, 'removeAssignUser'])->name('assign.destroy');
    Route::get('/edit-task/{id}', [TaskController::class, 'edit']);
    Route::post('/mark-as-done/{id}', [TaskController::class, 'markAsDone'])->name('done.post');

    Route::get('/user-list', [AdminController::class, 'userList']);
    Route::post('/edit-role', [AdminController::class, 'roleModal'])->name('edit.role-form');
    Route::post('/edit-role/{id}', [AdminController::class, 'editRoleUser'])->name('edit.role');
});
