<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index');
Route::view('/flights', 'flights');

Route::post('/get-token', [SearchController::class, 'getToken']);
Route::post('/search', [SearchController::class, 'search']);
Route::post('/get-flight-data', [SearchController::class, 'getFlightData']);

Route::get('/search-airports', [SearchController::class, 'searchAirports']);
