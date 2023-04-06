<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\ValueObject\ValueObjectInterface;

class ItemDescriptionValue implements ValueObjectInterface
{
    private ?string $value;

    /**
     * @param string|null $description
     */
    public function __construct(?string $description)
    {
        $this->value = $description;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value ?? '';
    }
}
