<?php

namespace App\Services;

use App\Interfaces\IApiRequestData;
use App\Models\Api;
use App\Providers\SearchProvider;
use App\Services\DTO\ApiInfo;
use Illuminate\Support\Facades\Http;

class SearchService
{
    public function search(string $api, IApiRequestData $request) : array
    {
        $searchProvider = new SearchProvider();
        $response = $searchProvider->sendRequest($api, $request);

        $responseJson = json_encode($response, JSON_UNESCAPED_UNICODE);
        $responseJson = str_replace('\\\\', '/', $responseJson);
        return json_decode($responseJson, true);
    }

    public function searchAirports(string $term) : array
    {
        $searchProvider = new SearchProvider();
        return $searchProvider->searchAirports($term);
    }

    public function constructRequest(string $api, IApiRequestData $request) : array
    {
        $searchProvider = new SearchProvider();
        return $searchProvider->constructRequest($api, $request);
    }
}
