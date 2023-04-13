<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Item;

use App\Application\DTO\Response\ResponseDTO;

class ListItemIdsResponseDTO extends ResponseDTO
{
    public function __construct(
        private readonly int $id,
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }
}
