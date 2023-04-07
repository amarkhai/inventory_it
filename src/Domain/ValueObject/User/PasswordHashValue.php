<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\Constant\Constant;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\ValueObject\ValueObjectInterface;

class PasswordHashValue implements ValueObjectInterface
{
    private string $value;

    /**
     * @param string $password_hash
     */
    public function __construct(string $password_hash)
    {
        //@todo проверки хеша
        $this->value = $password_hash;
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
