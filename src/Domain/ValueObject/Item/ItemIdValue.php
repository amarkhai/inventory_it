<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\ValueObject\ValueObjectInterface;

class ItemIdValue implements ValueObjectInterface
{
    private int $value;

    /**
     * @param int $id
     * @throws DomainWrongEntityParamException
     */
    public function __construct(int $id)
    {
        if ($id < 0) {
            throw new DomainWrongEntityParamException('id should be > 0');
        }

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
