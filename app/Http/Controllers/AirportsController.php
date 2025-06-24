<?php

namespace App\Http\Controllers;

use App\Services\AirportsService;
use Illuminate\Http\Request;

class AirportsController extends Controller
{
    public function __invoke(Request $request, AirportsService $airportsService) : array
    {
        $term = $request->query('term');
        return $airportsService->searchAirports($term);
    }
}
