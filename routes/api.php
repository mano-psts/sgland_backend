<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TenatDashboardController;
use App\Http\Controllers\FaultReportController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});


Route::post('visitor/signUp', [VisitorController::class, 'signUp']);
Route::post('visitor/login', [VisitorController::class, 'login']);
Route::post('visitor/forgotPassword', [VisitorController::class, 'forgotPassword']);
Route::any('visitor/requestOtpReset', [VisitorController::class, 'requestOtp']);
Route::post('visitor/verifyOtp', [VisitorController::class, 'verifyOtp']);


Route::post('visitor/uploadFileEmployee', [VisitorController::class, 'uploadFileEmployee']);
Route::post('admin/login', [VisitorController::class, 'adminLogin']);
Route::post('add/tenat', [SuperAdminController::class, 'addTenat']);


Route::post('onboard', [CustomerController::class, 'onBoard']);
Route::post('firstLogin', [CustomerController::class, 'firstTimelogin']);
Route::post('login', [CustomerController::class, 'login']);
Route::post('logout', [CustomerController::class, 'logout']);
Route::get('checkVerified/{email}', [CustomerController::class, 'checkVerified']);
Route::post('forgotPassword', [CustomerController::class, 'forgotPassword']);
Route::any('requestOtpReset', [CustomerController::class, 'requestOtp']);
Route::post('verifyOtp', [CustomerController::class, 'verifyOtp']);

Route::group(['middleware'=>'TokenVerify'],function(){
    Route::post('fault/equipmental',[FaultReportController::class, 'equipmental']);
    Route::post('fault/structural',[FaultReportController::class, 'structural']);
    Route::post('fault/toilet',[FaultReportController::class, 'toilet']);
    Route::post('fault/other',[FaultReportController::class, 'other']);

    Route::get('fault/getAll',[FaultReportController::class, 'getAll']);
    Route::get('fault/draft',[FaultReportController::class, 'draft']);
    Route::get('fault/getId/{id}',[FaultReportController::class, 'getId']);

    Route::get('fault/getEquipmental',[FaultReportController::class, 'getEquipmental']);
    Route::get('fault/getStructural',[FaultReportController::class, 'getStructural']);
    Route::get('fault/getToilet',[FaultReportController::class, 'getToilet']);
    Route::get('fault/getOther',[FaultReportController::class, 'getOther']);

    Route::get('downloadFile',[TenatDashboardController::class, 'downloadFile']);

});