<?php

namespace App\Services\DTO;

class ContextSettings
{
    private int $agency;
    private string $password;
    private int $user;
    private string $hash;
    private string $time;
    private string $locale;
    private string $command;
    private string $hashingString;

    public function __construct(int $agency, string $password, int $user, string $time, string $locale, string $command)
    {
        $this->agency = $agency;
        $this->password = $password;
        $this->user = $user;
        $this->time = $time;
        $this->locale = $locale;
        $this->command = $command;

        $this->hashingString = 'agency='.$agency.
            '&password='.$password.
            '&time='.$time.
            '&user='.$user;
        $this->hash = md5($this->hashingString);
    }

    public function getAgency(): int
    {
        return $this->agency;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getCommand(): string
    {
        return $this->command;
    }
    public function getHashingString(): string
    {
        return $this->hashingString;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function setHashingString(string $hashingString): void
    {
        $this->hashingString = $hashingString;
    }
}
