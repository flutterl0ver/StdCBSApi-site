<?php

namespace App\Services\DTO;

use App\Interfaces\IApiRequestData;

class FlightRequestData implements IApiRequestData
{
    private string $command;
    private array $data;

    public function __construct(string $command, array $data)
    {
        $this->command = $command;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
