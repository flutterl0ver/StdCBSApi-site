<?php

namespace App\Services;

use App\Interfaces\IApiRequestData;
use App\Models\SelectRequest;
use App\Providers\SearchProvider;
use App\Services\DTO\FlightRequestData;
use App\Services\DTO\SelectResultData;
use Symfony\Component\ErrorHandler\Error\FatalError;
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

        $selectRequest = new SelectRequest();
        $selectRequest->context_id = $contextId;
        $selectRequest->search_token = $request->getData()['token'];
        $selectRequest->request = json_encode($request->toArray());
        $selectRequest->status = 0;

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
        $response = $this->search($contextId, $request);

        $selectRequest = SelectRequest::where('request_token', $request->getToken())->first();
        if(!$selectRequest) return null;

        $selectRequest->response = json_encode($response);
        $selectRequest->save();

        return $response;
    }
}
