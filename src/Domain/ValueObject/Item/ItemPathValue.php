<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\Constant\Constant;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\ValueObject\ValueObjectInterface;

class ItemPathValue implements ValueObjectInterface
{
    private string $value;

    /**
     * @param string $value
     * @throws DomainWrongEntityParamException
     */
    public function __construct(string $value)
    {
        if (false === preg_match(Constant::ITEM_PATH_REGEX, $value)) {
            throw new DomainWrongEntityParamException('Wrong path value');
        }

        $this->value = $value;
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
