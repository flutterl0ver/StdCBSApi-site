<?php

use App\Http\Controllers\AirportsController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index');
Route::view('/flights', 'flights');
Route::view('/booking', 'booking');

Route::post('/search', [SearchController::class, 'search']);
Route::post('/search-result', [SearchController::class, 'searchResult']);
Route::post('/get-flight-data', [SearchController::class, 'getFlightData']);
Route::post('/select-result', [SearchController::class, 'selectResult']);

Route::get('/search-airports', AirportsController::class);
