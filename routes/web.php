<?php

use App\Http\Controllers\AirportsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index');
Route::view('/flights', 'flights');
Route::view('/booking', 'booking');
Route::view('/order', 'order');

Route::post('/search', [SearchController::class, 'search']);
Route::post('/search-result', [SearchController::class, 'searchResult']);
Route::post('/select', [SearchController::class, 'select']);
Route::post('/select-result', [SearchController::class, 'selectResult']);
Route::post('/get-flight-data', [SearchController::class, 'getFlightData']);
Route::post('/make-booking', BookingController::class);
Route::post('/get-order-data', [OrderController::class, 'getOrderData']);
Route::post('/display-order', [OrderController::class, 'displayOrder']);

Route::get('/search-airports', AirportsController::class);
