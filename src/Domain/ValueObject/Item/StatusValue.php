<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\ValueObject\ValueObjectInterface;

class StatusValue implements ValueObjectInterface
{
    private ItemStatusEnum $value;

    /**
     * @param ItemStatusEnum $status
     */
    public function __construct(ItemStatusEnum $status)
    {
        $this->value = $status;
    }

    public function getValue(): ItemStatusEnum
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value->getValue();
    }
}
