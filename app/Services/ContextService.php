<?php

namespace App\Services;

use App\DTO\ApiInfo;
use App\Models\BookingRequest;

class ContextService
{
    public function constructContext(int $contextId, string $command) : array
    {
        $apiInfo = new ApiInfo($contextId);
        $data = $apiInfo->getData();

        $timeStamp = date('Y-m-d\TH:i:sP');

        $hash = md5('agency='.$data['agency'].
            '&password='.$data['password'].
            '&time='.$timeStamp.
            '&user='.$data['user']);

        return [
            'agency' => $data['agency'],
            'user' => $data['user'],
            'hash' => $hash,
            'time' => $timeStamp,
            'locale' => 'ru',
            'command' => $command
        ];
    }

    public function getContextIdByOrder(string $orderToken) : ?int
    {
        $bookingRequest = BookingRequest::where('request_token', $orderToken)->first();

        return $bookingRequest? $bookingRequest->context_id : null;
    }
}
