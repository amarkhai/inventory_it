<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Item;

use App\Domain\ValueObject\ValueObjectInterface;
use Ramsey\Uuid\UuidInterface;

class OwnerIdValue implements ValueObjectInterface
{
    private UuidInterface $value;

    /**
     * @param UuidInterface $owner_id
     */
    public function __construct(UuidInterface $owner_id)
    {
        $this->value = $owner_id;
    }

    public function getValue(): UuidInterface
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value->toString();
    }
}
