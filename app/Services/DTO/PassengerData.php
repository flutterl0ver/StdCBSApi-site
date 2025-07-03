<?php

namespace App\Services\DTO;

use App\Models\Country;

class PassengerData
{
    private string $name;
    private string $surname;
    private ?string $patronymic;
    private string $type;
    private string $gender;
    private string $birth_date;
    private string $citizenship;
    private string $document_type;
    private string $document_number;
    private string $document_expire_date;
    private string $phone;
    private ?string $email;
    private ?string $no_email_status;

    public function __construct(string $name, string $surname, ?string $patronymic, string $type, string $gender, string $birth_date, string $citizenship, string $document_type, string $document_number, string $document_expire_date, string $phone, ?string $email, ?string $no_email_status)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
        $this->type = $type;
        $this->gender = $gender;
        $this->birth_date = $birth_date;
        $this->citizenship = $citizenship;
        $this->document_type = $document_type;
        $this->document_number = $document_number;
        $this->document_expire_date = $document_expire_date;
        $this->phone = $phone;
        $this->email = $email;
        $this->no_email_status = $no_email_status;

        $this->phone = str_replace(['-', '(', ')', '+', ' '], '', $this->phone);
    }

    public function toArray(): array
    {
        return [
            "passport" => [
                "firstName" => $this->name,
                "lastName" => $this->surname,
                "middleName" => $this->patronymic,
                "citizenship" => [
                    "code" => $this->citizenship,
                    "name" => Country::where('code', $this->citizenship)->first()->name_en
                ],
                "issued" => "",
                "expired" => $this->document_expire_date.'T00:00+00:00',
                "number" => $this->document_number,
                "type" => "INTERNAL",
                "birthday" => $this->birth_date.'T00:00+00:00',
                "gender" => strtoupper($this->gender)
            ],
            "type" => $this->type,
            "phoneType" => "HOME_PHONE",
            "phoneNumber" => substr($this->phone, 4, strlen($this->phone) - 4),
            "countryCode" => $this->phone[0],
            "areaCode" => substr($this->phone, 1, 3),
            "email" => $this->email ?? "",
            "isEmailRefused" => $this->no_email_status == "refused",
            "isEmailAbsent" => $this->no_email_status == "absent",
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getBirthDate(): string
    {
        return $this->birth_date;
    }

    public function getCitizenship(): string
    {
        return $this->citizenship;
    }

    public function getDocumentType(): string
    {
        return $this->document_type;
    }

    public function getDocumentNumber(): string
    {
        return $this->document_number;
    }

    public function getDocumentExpireDate(): string
    {
        return $this->document_expire_date;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getNoEmailStatus(): ?string
    {
        return $this->no_email_status;
    }
}
