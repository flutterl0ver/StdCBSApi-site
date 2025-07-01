<?php

namespace App\Services\DTO;

use App\Interfaces\IApiRequestData;

class BookingRequestData implements IApiRequestData
{
    private string $customer_phone;
    private string $customer_email;
    private string $token;
    /** @var PassengerData[] */
    private array $passengers;

    public function __construct(string $customer_phone, string $customer_email, string $token, array $passengers)
    {
        $this->customer_phone = $customer_phone;
        $this->customer_email = $customer_email;
        $this->token = $token;
        $this->passengers = $passengers;
    }

    public function getCommand(): string
    {
        return 'CREATEBOOKING';
    }

    public function toArray(): array
    {
        return [];
    }


    public function getPassengers(): array
    {
        return $this->passengers;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    public function getCustomerPhone(): string
    {
        return $this->customer_phone;
    }
}
