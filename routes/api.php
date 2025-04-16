<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//Check Token if exists
Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('logout', [AuthController::class, 'logout']);
    route::post('refresh', [AuthController::class, 'refresh']);
    // route::post('employee', [EmployeeController::class, 'store']);
    route::apiResource('employee', EmployeeController::class);
});
