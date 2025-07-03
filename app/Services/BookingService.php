<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\SelectRequest;
use App\Providers\SearchProvider;
use App\Services\DTO\BookingRequestData;
use App\Services\DTO\OrderRequestsData;

class BookingService
{
    public function createBooking(BookingRequestData $data): array
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
        $bookingRequest->status = 0;
        $bookingRequest->select_request_uid = $selectRequest->uid;

        if(!$response)
        {
            $bookingRequest->errors = 'RESPONSE IS NULL';
        }
        else if($response['respond']['token'] != '')
        {
            $bookingRequest->request_token = $response['respond']['token'];
        }
        else
        {
            $bookingRequest->errors = json_encode($response['respond']['messages']['message']);
        }

        $bookingRequest->save();
        $a = new OrderRequestsData('DISPLAYORDER', $response['respond']['token']);
        return $searchProvider->constructRequest($selectRequest->context_id, $a);
    }
}
