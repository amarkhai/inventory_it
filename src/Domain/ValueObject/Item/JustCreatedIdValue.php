<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\ValueObject\ValueObjectInterface;

class JustCreatedIdValue implements ValueObjectInterface
{
    private int $value;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->value = $id;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }
}
