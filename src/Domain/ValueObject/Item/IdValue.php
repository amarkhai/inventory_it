<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\ValueObject\ValueObjectInterface;

class IdValue implements ValueObjectInterface
{
    private ?int $value;

    /**
     * @param int|null $id
     */
    public function __construct(int $id = null)
    {
        $this->value = $id;
    }

    /**
     * @return int|null
     */
    public function getValue(): ?int
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
