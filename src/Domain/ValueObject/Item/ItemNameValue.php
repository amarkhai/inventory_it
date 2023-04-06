<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\ValueObject\ValueObjectInterface;

class ItemNameValue implements ValueObjectInterface
{
    private const MIN_STRLEN = 2;
    private const MAX_STRLEN = 100;
    private string $value;

    /**
     * @param string $name
     * @throws DomainWrongEntityParamException
     */
    public function __construct(string $name)
    {
        $strlen = mb_strlen($name);
        if ($strlen < self::MIN_STRLEN || $strlen > self::MAX_STRLEN) {
            throw new DomainWrongEntityParamException('Wrong item name strlen');
        }

        $this->value = $name;
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
