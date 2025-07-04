<?php

namespace App\DTO;

use App\Interfaces\IApiRequestData;

class OrderRequestData implements IApiRequestData
{
    private string $command;
    private string $token;

    public function __construct(string $command, string $token)
    {
        $this->command = $command;
        $this->token = $token;
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token
        ];
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
