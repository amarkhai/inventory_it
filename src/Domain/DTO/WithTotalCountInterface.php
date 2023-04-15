<?php

namespace App\Domain\DTO;

interface WithTotalCountInterface
{
    public function getData(): array;
    public function getTotalCount(): int;
}
