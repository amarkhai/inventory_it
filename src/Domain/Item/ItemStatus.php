<?php

namespace App\Domain\Item;

enum ItemStatus: string
{
    case active = "active";
    case deleted = "deleted";

    public function status(): string
    {
        return $this->value;
    }
}
