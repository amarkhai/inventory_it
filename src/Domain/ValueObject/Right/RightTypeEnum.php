<?php

namespace App\Domain\ValueObject\Right;

enum RightTypeEnum: string
{
    case ro = "ro";
    case rw = "rw";

    public function getValue(): string
    {
        return $this->value;
    }
}
