<?php

namespace App\Services;

use App\Interfaces\IApiRequestData;
use App\Models\SelectRequest;
use App\Providers\SearchProvider;
use App\Services\DTO\FlightRequestData;
use App\Services\DTO\SelectResultData;
use function Laravel\Prompts\search;

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

    public function select(int $contextId, FlightRequestData $request) : array
    {
        $response = $this->search($contextId, $request);

        $bookingRequest = new SelectRequest();
        $bookingRequest->context_id = $contextId;
        $bookingRequest->search_token = $request->getData()['token'];
        $bookingRequest->request = json_encode($request->toArray());
        $bookingRequest->request_token = $response ? $response['respond']['token'] : null;
        $bookingRequest->status = 0;
        $bookingRequest->save();

        return $response;
    }

    public function selectResult(int $contextId, SelectResultData $request) : array
    {
        $response = $this->search($contextId, $request);

        $bookingRequest = SelectRequest::where('request_token', $request->getToken())->first();
        $bookingRequest->response = json_encode($response);
        $bookingRequest->save();

        return $response;
    }
}
