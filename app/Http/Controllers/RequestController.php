<?php

namespace App\Http\Controllers;

use App\Services\DTO\FlightRequestData;
use App\Services\DTO\SearchData;
use App\Services\DTO\SearchResultData;
use App\Services\SearchService;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function searchFlights(Request $request, SearchService $searchService) : array
    {
        $requestData = json_decode($request->post('data'), associative: true);

        $data = new SearchData(
            $requestData['from'],
            $requestData['to'],
            $requestData['date_to'],
            $requestData['date_from'],
            $requestData['adults'],
            $requestData['children'],
            $requestData['infants']
        );

        return $searchService->constructRequest('ttbooking', $data);
    }

    public function searchResult(Request $request, SearchService $searchService) : array
    {
        $requestData = json_decode($request->post('data'), associative: true);

        $data = new SearchResultData(
            $requestData['token']
        );

        return $searchService->constructRequest('ttbooking', $data);
    }

    public function flight(Request $request, SearchService $searchService) : array
    {
        $requestData = json_decode($request->post('data'), associative: true);

        $command = $requestData['command'];
        unset($requestData['command']);
        $data = new FlightRequestData(
            $command,
            $requestData,
        );

        return $searchService->constructRequest('ttbooking', $data);
    }
}
