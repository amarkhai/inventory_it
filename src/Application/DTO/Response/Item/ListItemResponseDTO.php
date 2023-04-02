<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Item;

use App\Application\DTO\Response\ResponseDTO;

class ListItemResponseDTO extends ResponseDTO
{
    private int $id;
    private ?string $name;
    private ?string $description;
    private ?string $path;

    /**
     * @param int $id
     * @param string|null $name
     * @param string|null $description
     * @param string|null $path
     */
    public function __construct(
        int $id,
        ?string $name,
        ?string $description,
        ?string $path,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'path' => $this->getPath(),
        ];
    }
}
