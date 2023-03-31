<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\ValueObject\ValueObjectInterface;
use Ramsey\Uuid\UuidInterface;

class TemporaryIdValue implements ValueObjectInterface
{
    private string $value;

    /**
     * @param string $temporary_id
     */
    public function __construct(string $temporary_id)
    {
        $this->value = $temporary_id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
