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
})->name('insert');
// Edit Page
Route::get('edit/{id}', 'App\Http\Controllers\ShopCart@show');

Route::post('create','App\Http\Controllers\ShopCart@store');
Route::get('view-records','App\Http\Controllers\ShopCart@viewRecords')->name('view-records');
Route::get('delete/{id}','App\Http\Controllers\ShopCart@destroy');

// Edit API
Route::patch('products/edit/{id}','App\Http\Controllers\ShopCart@edit')->name('products.edit');

// For Stripe Checkout Element
Route::get('/checkout', function () {
  return view('checkout');
});