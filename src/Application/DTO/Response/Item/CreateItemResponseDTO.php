<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Item;

use App\Application\DTO\Response\ResponseDTO;

class CreateItemResponseDTO extends ResponseDTO
{
    public function __construct(
        private readonly int $id,
        private readonly string $temporary_id,
        private readonly ?string $path
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTemporaryId(): string
    {
        return $this->temporary_id;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'temporary_id' => $this->getTemporaryId(),
            'path' => $this->getPath(),
        ];
    }
}
