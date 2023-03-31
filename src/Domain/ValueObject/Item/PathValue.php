<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\ValueObject\ValueObjectInterface;

class PathValue implements ValueObjectInterface
{
    private string $value;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->value = $path;
    }

    /**
     * @return string
     */
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
