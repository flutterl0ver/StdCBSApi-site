<?php

namespace App\DTO;

use App\Interfaces\IApiRequestData;

class SelectResultData implements IApiRequestData
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getCommand(): string
    {
        return 'SELECTRESULT';
    }

    public function toArray(): array
    {
        return ['token' => $this->token];
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
