<?php

namespace App\Services;

use App\Services\DTO\ApiInfo;

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
}
