<?php

namespace App\Interfaces;

interface IApiRequestData
{
    public function toArray(): array;
    public function getCommand(): string;
}
