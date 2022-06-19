<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('insert', function() {
  return view('products-create');
});
Route::post('create','App\Http\Controllers\ShopCart@store');
Route::get('view-records','App\Http\Controllers\ShopCart@index');