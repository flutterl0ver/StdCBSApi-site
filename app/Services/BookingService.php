<?php

namespace App\Services;

use App\Models\SelectRequest;
use App\Providers\SearchProvider;
use App\Services\DTO\BookingRequestData;

class BookingService
{
    public function createBooking(BookingRequestData $data): array
    {
        $selectRequest = SelectRequest::select(['context_id', 'request'])
            ->where('request_token', $data->getToken())
            ->first();

        $flightsGroup = json_decode($selectRequest->request, true)['flightsGroup'];
        $data->setFlightsGroup($flightsGroup);

        $searchProvider = new SearchProvider();
        return $searchProvider->sendRequest($selectRequest->context_id, $data);
    }
}
