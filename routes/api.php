<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TermometroController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/login', 
    [AuthController::class, 'login']
);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth', [AuthController::class, 'me']);

    
    Route::get('/termometro', 
        [TermometroController::class, 'index']
    )->name('termometro.index');
    
});

//fallback route
Route::any('{any}', function () {
    return response()->json([
        'message' => 'Route not found',
    ], 404);
})->where('any', '.*');