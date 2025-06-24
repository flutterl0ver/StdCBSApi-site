<?php

namespace App\Providers;

use App\Interfaces\IApiRequestData;
use App\Models\Api;
use App\Services\ContextService;
use Illuminate\Support\Facades\Http;

class SearchProvider
{
    public function sendRequest(string $api, IApiRequestData $data) : array
    {
        $request = $this->constructRequest($api, $data);

        $url = Api::where('name', $api)->first()->url;

        return Http::post($url, $request)->json();
    }

    public function searchAirports(string $term) : array
    {
        return Http::post('https://dev.ttbooking.ru/module/qsearch', ['term' => $term])->json();
//        return Http::get('https://dev.ttbooking.ru/module/qsearch', ['term' => $term])->json();
    }

    public function constructRequest(string $api, IApiRequestData $data) : array
    {
        $contextService = new ContextService();

        $context = $contextService->constructContext($api, $data->getCommand());

        return [
            'context' => [
                'agency' => $context->getAgency(),
                'user' => $context->getUser(),
                'hash' => $context->getHash(),
                'time' => $context->getTime(),
                'locale' => $context->getLocale(),
                'command' => $context->getCommand(),
            ],
            'parameters' => $data->toArray()
        ];
    }
}
