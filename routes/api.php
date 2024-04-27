<?php

use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [UserAuthController::class, 'register']);


// Route::get('/users', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
