<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Right;

use App\Application\DTO\Response\ResponseDTO;

class ListRightResponseDTO extends ResponseDTO
{
    public function __construct(
        private string $id,
        private int $item_id,
        private string $user_id,
        private string $type
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     */
    public function setItemId(int $item_id): void
    {
        $this->item_id = $item_id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'item_id' => $this->getItemId(),
            'user_id' => $this->getUserId(),
            'type' => $this->getType(),
        ];
    }
}
