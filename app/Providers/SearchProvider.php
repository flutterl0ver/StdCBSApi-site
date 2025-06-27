<?php

namespace App\Providers;

use App\Interfaces\IApiRequestData;
use App\Models\Api;
use App\Services\ContextService;
use Illuminate\Support\Facades\Http;

class SearchProvider
{
    public function sendRequest(int $contextId, IApiRequestData $data) : array
    {
        $request = $this->constructRequest($contextId, $data);

        $url = Api::where('id', $contextId)->first()->url;

        return Http::post($url, $request)->json();
    }

    public function constructRequest(int $contextId, IApiRequestData $data) : array
    {
        $contextService = new ContextService();

        $context = $contextService->constructContext($contextId, $data->getCommand());

        return [
            'context' => $context,
            'parameters' => $data->toArray()
        ];
    }
}
