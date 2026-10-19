<?php

use App\Http\Controllers\Api\AttedanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
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
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/attedances/all', [AttedanceController::class, 'all']);
    Route::get('/attedances/getByCompany', [AttedanceController::class, 'getByCompany']);
    Route::post('/attedances/checkin', [AttedanceController::class, 'checkin']);
    Route::post('/attedances/checkout', [AttedanceController::class, 'checkout']);

    Route::apiResource('/companies', CompanyController::class);
});
