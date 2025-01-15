<?php

use App\Http\Controllers\Api\SeatTemplateController;
use App\Http\Controllers\PaymentController;
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
Route::post('/index',[PaymentController::class,'index']);
Route::get('/checkout',[PaymentController::class,'checkout']);
// Route::get('/getPaymentStatus',[PaymentController::class,'getPaymentStatus']);
Route::post('/store',[SeatTemplateController::class,'store']);
Route::put('{id}/updateSeatStructure',[SeatTemplateController::class,'updateSeatStructure']);
Route::get('{id}/getJson',[SeatTemplateController::class,'getJson']);
Route::get('{id}/show',[SeatTemplateController::class,'show']);
