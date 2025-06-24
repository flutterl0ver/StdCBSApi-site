<?php

namespace App\Services\DTO;

use App\Models\Api;

class ApiInfo
{
    private string $password;
    private string $user;
    private string $url;
    private int $agency;

    public function __construct(string $apiName) {
        $apiInfo = Api::where('name', $apiName)->first();

        $this->password = $apiInfo->password;
        $this->user = $apiInfo->user;
        $this->url = $apiInfo->url;
        $this->agency = $apiInfo->agency;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAgency(): int
    {
        return $this->agency;
    }
}
