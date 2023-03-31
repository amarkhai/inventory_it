<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Item;

use App\Application\DTO\Response\ResponseDTO;

class ListItemResponseDTO extends ResponseDTO
{
    private int $id;
    private string $name;
    private string $path;

    /**
     * @param int $id
     * @param string $name
     * @param string $path
     */
    public function __construct(
        int $id,
        string $name,
        string $path,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->path = $path;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'path' => $this->getPath()
        ];
    }
}
