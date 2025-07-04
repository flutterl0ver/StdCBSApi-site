<?php

namespace App\Services;

use App\DTO\BookingRequestData;
use App\DTO\OrderRequestData;
use App\Models\BookingRequest;
use App\Models\SelectRequest;
use App\Providers\SearchProvider;

class BookingService
{
    public function createBooking(BookingRequestData $data): ?array
    {
        $selectRequest = SelectRequest::select(['context_id', 'request', 'uid'])
            ->where('request_token', $data->getToken())
            ->first();

        $flightsGroup = json_decode($selectRequest->request, true)['flightsGroup'];
        $data->setFlightsGroup($flightsGroup);

        $searchProvider = new SearchProvider();
        $response = $searchProvider->sendRequest($selectRequest->context_id, $data);

        $bookingRequest = new BookingRequest();
        $bookingRequest->context_id = $selectRequest->context_id;
        $bookingRequest->request = json_encode($data->toArray());
        $bookingRequest->select_request_uid = $selectRequest->uid;

        if (!$response)
        {
            $bookingRequest->errors = 'RESPONSE IS NULL';
        }
        else if ($response['respond']['token'] != '')
        {
            $bookingRequest->request_token = $response['respond']['token'];
        }
        else
        {
            $bookingRequest->errors = json_encode($response['respond'], JSON_UNESCAPED_UNICODE);
        }

        $bookingRequest->save();

        return $response;
    }

    public function displayOrder($token): array
    {
        $bookingRequest = BookingRequest::where('request_token', $token)->first();
        if(!$bookingRequest)
        {
            $bookingRequest = new BookingRequest();
            $bookingRequest->request_token = $token;
            $bookingRequest->context_id = 1; // TODO: УБРАТЬ ЭТОТ КОСТЫЛЬ!!!!!!
        }
        if($bookingRequest->response) return json_decode($bookingRequest->response, true);

        $searchService = new SearchService();
        $request = new OrderRequestData('DISPLAYORDER', $token);
        $response = $searchService->sendRequest($bookingRequest->context_id, $request);
        $bookingRequest->response = json_encode($response, JSON_UNESCAPED_UNICODE);

        if($response && $response['respond']['token'] != '') $bookingRequest->save();

        return $response;
    }
}
