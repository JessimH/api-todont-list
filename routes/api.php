<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

//Auth
Route::post('auth/register', [\App\Http\Controllers\ApiTokenController::class, 'register']);
Route::post('auth/login', [\App\Http\Controllers\ApiTokenController::class, 'login']);

Route::middleware('auth:sanctum')->post('auth/me', [\App\Http\Controllers\ApiTokenController::class, 'me']);

Route::middleware('auth:sanctum')->post('auth/logout', [\App\Http\Controllers\ApiTokenController::class, 'logout']);

Route::middleware('auth:sanctum')->apiResource('tasks', \App\Http\Controllers\PostController::class );
