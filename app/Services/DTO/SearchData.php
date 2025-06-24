<?php

namespace App\Services\DTO;

use App\Interfaces\IApiRequestData;

class SearchData implements IApiRequestData
{
    private string $from;
    private string $to;
    private string $date_to;
    private ?string $date_from;
    private int $adults;
    private int $children;
    private int $infants;

    public function __construct(string $from, string $to, string $date_to, ?string $date_from, int $adults, int $children, int $babies)
    {
        $this->from = $from;
        $this->to = $to;
        $this->date_to = $date_to;
        $this->date_from = $date_from;
        $this->adults = $adults;
        $this->children = $children;
        $this->infants = $babies;
    }

    public function toArray() : array
    {
        $data = [
            'route' => [
                [
                    'date' => $this->date_to,
                    'locationBegin' => [
                        'code' => $this->from,
                        'name' => ''
                    ],
                    'locationEnd' => [
                        'code' => $this->to,
                        'name' => ''
                    ]
                ]
            ],
            'seats' => [
                [
                    'count' => $this->adults,
                    'passengerType' => 'ADULT'
                ]
            ],
            'serviceClass' => 'ECONOMY',
            'skipConnected' => '',
            'eticketsOnly' => true,
            'mixedVendors' => true,
            'preferredAirlines' => [],
        ];

        if ($this->date_from != null)
        {
            $routeTo = $data['route'][0];
            $data['route'][] = [
                'date' => $this->date_from,
                'locationBegin' => $routeTo['locationEnd'],
                'locationEnd' => $routeTo['locationBegin'],
            ];
        }

        if($this->children > 0)
        {
            $data['seats'][] = [
                'count' => $this->children,
                'passengerType' => 'CHILD'
            ];
        }

        if($this->infants > 0)
        {
            $data['seats'][] = [
                'count' => $this->infants,
                'passengerType' => 'INFANT'
            ];
        }

        if($this->from == '' || $this->to == '')
        {
            $data['route'] = [];
        }

        return $data;
    }

    public function getCommand(): string
    {
        return 'SEARCHFLIGHTS';
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getDateTo(): string
    {
        return $this->date_to;
    }

    public function getDateFrom(): ?string
    {
        return $this->date_from;
    }

    public function getAdults(): int
    {
        return $this->adults;
    }

    public function getChildren(): int
    {
        return $this->children;
    }

    public function getInfants(): int
    {
        return $this->infants;
    }
}
