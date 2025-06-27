<?php

namespace App\Services;

use App\Interfaces\IApiRequestData;
use App\Models\Api;
use App\Providers\SearchProvider;
use App\Services\DTO\ApiInfo;
use Illuminate\Support\Facades\Http;

class SearchService
{
    public function search(int $contextId, IApiRequestData $request) : array
    {
        $searchProvider = new SearchProvider();
        $response = $searchProvider->sendRequest($contextId, $request);

        $responseJson = json_encode($response, JSON_UNESCAPED_UNICODE);
        $responseJson = str_replace('\\\\', '/', $responseJson);
        return json_decode($responseJson, true);
    }
}
