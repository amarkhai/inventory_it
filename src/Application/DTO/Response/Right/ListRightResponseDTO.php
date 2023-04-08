<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Right;

use App\Application\DTO\Response\ResponseDTO;

class ListRightResponseDTO extends ResponseDTO
{
    public function __construct(
        private string $id,
        private string $path,
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
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
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
            'path' => $this->getPath(),
            'user_id' => $this->getUserId(),
            'type' => $this->getType(),
        ];
    }
}
