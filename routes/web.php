<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return redirect()->route('login.form');
});

Route::get('/login', 
    [LoginController::class, 'showLoginForm']
)->name('login.form');

Route::post('/login', 
    [LoginController::class, 'login']
)->name('login');

Route::post('/logout', 
    [LoginController::class, 'logout']
)->name('auth.logout');

Route::middleware('auth:web')->group(function () {

    Route::get('/menu', function () {
        return view('layout.menu');
    })->name('menu');

    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    Route::get('/users', 
        [UserController::class, 'index']
    )->name('users.index');

    Route::get('/users/create', 
        [UserController::class, 'create']
    )->name('users.create');

    Route::get('/users/{user}/edit', 
        [UserController::class, 'edit']
    )->name('users.edit');

    Route::post('/users', 
        [UserController::class, 'store']
    )->name('users.store');

    Route::put('/users/{user}', 
        [UserController::class, 'update']
    )->name('users.update');

    Route::delete('/users/{user}', 
        [UserController::class, 'destroy']
    )->name('users.destroy');

});

//fallback
Route::fallback(function () {
    return redirect()->route('login.form');
});