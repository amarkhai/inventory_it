<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\Constant\Constant;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\ValueObject\ValueObjectInterface;

class FirstNameValue implements ValueObjectInterface
{
    private string $value;

    /**
     * @param string $name
     * @throws DomainWrongEntityParamException
     */
    public function __construct(string $name)
    {
        $strlen = mb_strlen($name);
        if (
            $strlen < Constant::USER_FIRST_AND_SECOND_NAME_MIN_LENGTH
            || $strlen > Constant::USER_FIRST_AND_SECOND_NAME_MAX_LENGTH
        ) {
            throw new DomainWrongEntityParamException('Wrong first name strlen');
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
