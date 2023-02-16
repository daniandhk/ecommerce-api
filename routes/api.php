<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:api', 'role'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::middleware(['scope:admin,user'])->get('/user', function (Request $request) {
        $user = $request->user();
        $user->role = $user->role()->first()->role;
        return $user;
    });
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
