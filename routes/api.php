<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopCart;
use App\Http\Controllers\secret;
use App\Http\Controllers\paymentForm;
use App\Http\Controllers\successmail;

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

// Route::apiResource('products', 'App\Http\Controllers\ShopCart@index');
Route::apiResource('products', ShopCart::class);
Route::apiResource('checkout2', secret::class);
Route::apiResource('checkout3', paymentForm::class);
Route::apiResource('checkout4', successmail::class);