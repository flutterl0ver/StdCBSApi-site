<?php

namespace App\Services;

use App\Services\DTO\ApiInfo;
use App\Services\DTO\ContextSettings;

class ContextService
{
    public function constructContext(string $api, string $command) : ContextSettings
    {
        $apiInfo = new ApiInfo($api);

        return new ContextSettings(
            $apiInfo->getAgency(),
            $apiInfo->getPassword(),
            $apiInfo->getUser(),
            date('Y-m-d\TH:i:sP'),
            'ru',
            $command
        );
    }
}
