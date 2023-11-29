<?php

use App\Http\Controllers\api\AssistanceRequestController;
use App\Http\Controllers\Api\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'getUser']);
    Route::get('/user/revoke', [AuthController::class, 'revokeToken']);
});

Route::post('/sanctum/token', [AuthController::class, 'generateToken']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/assistance_request/getVehicles/client_id/{id}', [AssistanceRequestController::class, 'getVehicles']);
Route::get('/assistance_request/getAll/client_id/{id}', [AssistanceRequestController::class, 'getAssistanceRequests']);

Route::post('/assistance_request/clientRequestAssistance', [AssistanceRequestController::class, 'clientRequestAssistance']);


