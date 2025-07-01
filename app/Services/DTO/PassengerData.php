<?php

namespace App\Services\DTO;

class PassengerData
{
    private string $name;
    private string $surname;
    private ?string $patronymic;
    private string $gender;
    private string $birth_date;
    private string $citizenship;
    private string $document_type;
    private int $document_number;
    private string $document_expire_date;
    private string $phone;
    private ?string $email;
    private ?string $no_email_status;
}
