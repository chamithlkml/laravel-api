<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [UserAuthController::class, 'register']);
Route::post('auth/login', [UserAuthController::class, 'login']);

Route::post('products', [ProductController::class, 'create'])
  ->middleware('auth:sanctum');

Route::get('products', [ProductController::class, 'index'])
  ->middleware('auth:sanctum');