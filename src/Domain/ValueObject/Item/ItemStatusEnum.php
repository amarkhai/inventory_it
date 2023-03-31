<?php

namespace App\Domain\ValueObject\Item;

enum ItemStatusEnum: string
{
    case active = "active";
    case deleted = "deleted";

    public function getValue(): string
    {
        return $this->value;
    }
}
