<?php

use Illuminate\Http\Request;

use Modules\Tenat\Http\Controllers\FaultController;
use Modules\Tenat\Http\Controllers\TenatLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/tenat', function (Request $request) {
    return $request->user();
});
// Route::post('fault/equipmental',[FaultController::class, 'equipmental']);
// Route::post('fault/structural',[FaultController::class, 'structural']);
// Route::post('fault/toilet',[FaultController::class, 'toilet']);
// Route::post('fault/other',[FaultController::class, 'other']);


// Route::get('fault/getAll',[FaultController::class, 'getAll']);
// Route::get('fault/draft',[FaultController::class, 'draft']);
// Route::get('fault/getId/{id}',[FaultController::class, 'getId']);

// Route::get('fault/getEquipmental',[FaultController::class, 'getEquipmental']);
// Route::get('fault/getStructural',[FaultController::class, 'getStructural']);
// Route::get('fault/getToilet',[FaultController::class, 'getToilet']);
// Route::get('fault/getOther',[FaultController::class, 'getOther']);

Route::post('tenat/firstTimelogin', [TenatLoginController::class, 'firstTimelogin']);
Route::post('tenat/login', [TenatLoginController::class, 'login']);
Route::get('tenat/logout', [TenatLoginController::class, 'logout']);
Route::post('tenat/forgotPasswordTenat', [TenatLoginController::class, 'forgotPasswordTenat']);

Route::any('tenat/requestOtp', [TenatLoginController::class, 'requestOtp']);
Route::post('tenat/verifyOtp', [TenatLoginController::class, 'verifyOtp']);