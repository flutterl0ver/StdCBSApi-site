<?php

namespace App\Services\DTO;

use App\Models\Api;

class ApiInfo
{
    private string $url;
    private array $data;

    public function __construct(int $contextId) {
        $apiInfo = Api::where('id', $contextId)->first();

        $this->url = $apiInfo->url;
        $this->data = json_decode($apiInfo->data, true);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
