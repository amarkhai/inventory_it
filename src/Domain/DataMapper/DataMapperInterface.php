<?php

declare(strict_types=1);

namespace App\Domain\DataMapper;

interface DataMapperInterface
{
    public function map(array $row): mixed;
}
