<?php

namespace App\DTO;

use App\Interfaces\IApiRequestData;

class BookingRequestData implements IApiRequestData
{
    private string $customer_phone;
    private string $customer_email;
    private string $token;
    /** @var PassengerData[] */
    private array $passengers;
    private array $flightsGroup;

    public function __construct(string $customer_phone, string $customer_email, string $token, array $passengers)
    {
        $this->customer_phone = $customer_phone;
        $this->customer_email = $customer_email;
        $this->token = $token;
        $this->passengers = $passengers;

        $this->customer_phone = str_replace(['-', '(', ')', '+', ' '], '', $this->customer_phone);
    }

    public function getCommand(): string
    {
        return 'CREATEBR';
    }

    public function toArray(): array
    {
        $firstPassenger = $this->passengers[0];
        $data = [
            "token" => $this->token,
            "flightsGroup" => $this->flightsGroup,
            "customer" => [
                "name" => $firstPassenger->getSurname().' '.$firstPassenger->getName().' '.$firstPassenger->getPatronymic(),
                "email" => $this->customer_email,
                "countryCode" => $this->customer_phone[0],
                "areaCode" => substr($this->customer_phone, 1, 3),
                "phoneNumber" => substr($this->customer_phone, 4, strlen($this->customer_phone) - 4)
            ],
            "passengers" => [
                "passenger" => []
            ]
        ];
        foreach ($this->passengers as $passenger)
        {
            $data['passengers']['passenger'][] = $passenger->toArray();
        }
        return $data;
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

    public function getFlightsGroup(): array
    {
        return $this->flightsGroup;
    }

    public function setFlightsGroup(array $flightsGroup): void
    {
        $this->flightsGroup = $flightsGroup;
    }
}
