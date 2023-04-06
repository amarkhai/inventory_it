<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Item;

use App\Application\DTO\Response\ResponseDTO;

class UpdateItemResponseDTO extends ResponseDTO
{
    public function __construct(
        private readonly bool $result,
    ) {
    }

    /**
     * @return bool
     */
    public function getResult(): bool
    {
        return $this->result;
    }

    public function jsonSerialize(): bool
    {
        return $this->getResult();
    }
}
