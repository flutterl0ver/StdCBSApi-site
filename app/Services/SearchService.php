<?php

namespace App\Services;

use App\Interfaces\IApiRequestData;
use App\Models\SearchRequest;
use App\Models\SelectRequest;
use App\Providers\SearchProvider;
use App\Services\DTO\FlightRequestData;
use App\Services\DTO\SearchData;
use App\Services\DTO\SearchResultData;
use App\Services\DTO\SelectResultData;

class SearchService
{
    public function sendRequest(int $contextId, IApiRequestData $request) : array
    {
        $searchProvider = new SearchProvider();
        $response = $searchProvider->sendRequest($contextId, $request);

        $responseJson = json_encode($response, JSON_UNESCAPED_UNICODE);
        $responseJson = str_replace('\\\\', '/', $responseJson);

        return json_decode($responseJson, true);
    }

    public function search(int $contextId, SearchData $request) : array
    {
        $response = $this->sendRequest($contextId, $request);

        $searchRequest = new SearchRequest();
        if(!$response)
        {
            $searchRequest->errors = 'RESPONSE IS NULL';
        }
        else if($response['respond']['token'] != '')
        {
            $searchRequest->token = $response['respond']['token'];
        }
        else
        {
            $searchRequest->errors = $response['respond'];
        }
        $searchRequest->request = json_encode($request->toArray(), JSON_UNESCAPED_UNICODE);
        $searchRequest->context_id = $contextId;

        $searchRequest->save();

        return $response;
    }

    public function searchResult(int $contextId, SearchResultData $request) : ?array
    {
        $searchRequest = SearchRequest::where('token', $request->getToken())->first();

        if(!$searchRequest)
        {
            $searchRequest = new SearchRequest();
            $searchRequest->token = $request->getToken();
            $searchRequest->context_id = $contextId;
        }

        if(!$searchRequest->response)
        {
            $response = $this->sendRequest($contextId, $request);
            $searchRequest->response = json_encode($response, JSON_UNESCAPED_UNICODE);
        }
        else $response = json_decode($searchRequest->response, true);

        $searchRequest->save();

        return $response;
    }

    public function select(int $contextId, FlightRequestData $request) : array
    {
        $response = $this->sendRequest($contextId, $request);

        $selectRequest = new SelectRequest();
        $selectRequest->context_id = $contextId;
        $selectRequest->search_token = $request->getData()['token'];
        $selectRequest->request = json_encode($request->toArray());

        if(!$response)
        {
            $selectRequest->errors = 'RESPONSE IS NULL';
        }
        else if($response['respond']['token'] != '')
        {
            $selectRequest->request_token = $response['respond']['token'];
        }
        else
        {
            $selectRequest->errors = json_encode($response['respond'], JSON_UNESCAPED_UNICODE);
        }

        $selectRequest->save();

        return $response;
    }

    public function selectResult(int $contextId, SelectResultData $request) : ?array
    {
        $selectRequest = SelectRequest::where('request_token', $request->getToken())->first();

        if(!$selectRequest)
        {
            $selectRequest = new SelectRequest();
            $selectRequest->context_id = $contextId;
            $selectRequest->request_token = $request->getToken();
        }

        if (!$selectRequest->response)
        {
            $response = $this->sendRequest($contextId, $request);
            $selectRequest->response = json_encode($response, JSON_UNESCAPED_UNICODE);
        }
        else $response = json_decode($selectRequest->response, true);

        $selectRequest->save();

        return $response;
    }
}
