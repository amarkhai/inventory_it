<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

interface ValueObjectInterface
{
    /**
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * @return string
     */
    public function __toString(): string;
}
