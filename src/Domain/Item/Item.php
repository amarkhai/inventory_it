<?php

declare(strict_types=1);

namespace App\Domain\Item;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

/**
 * @param $id int|null ID, сгенерированный на сервере при сохранении записи
 * @param $t_id UuidInterface|null ID, сгенерированный на клиенте при создании объекта
 */
class Item implements JsonSerializable
{
    private ?int $id;
    private ?UuidInterface $t_id;
    private ?string $path;
    private ?string $parentPath;
    private ItemStatus $status;
    private UuidInterface $owner_id;
    private ?string $name = null;
    private ?string $description = null;

    /**
     * @param int|null $id
     * @param UuidInterface|null $t_id
     * @param string|null $path
     * @param ItemStatus $status
     * @param UuidInterface $owner_id
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct(
        ?int $id,
        ?UuidInterface $t_id,
        ?string $path,
        ?string $parentPath,
        ItemStatus $status,
        UuidInterface $owner_id,
        ?string $name,
        ?string $description
    ) {
        $this->id = $id;
        $this->t_id = $t_id;
        $this->path = $path;
        $this->parentPath = $parentPath;
        $this->status = $status;
        $this->owner_id = $owner_id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
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
     * @return ItemStatus
     */
    public function getStatus(): ItemStatus
    {
        return $this->status;
    }

    /**
     * @param ItemStatus $status
     */
    public function setStatus(ItemStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return UuidInterface
     */
    public function getOwnerId(): UuidInterface
    {
        return $this->owner_id;
    }

    /**
     * @param UuidInterface $owner_id
     */
    public function setOwnerId(UuidInterface $owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            't_id' => $this->t_id,
            'path' => $this->path,
            'owner_id' => $this->owner_id,
            'status' => $this->status,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    /**
     * @return UuidInterface|null
     */
    public function getT_id(): ?UuidInterface
    {
        return $this->t_id;
    }

    /**
     * @param UuidInterface|null $t_id
     */
    public function setT_id(?UuidInterface $t_id): void
    {
        $this->t_id = $t_id;
    }

    /**
     * @return string|null
     */
    public function getParentPath(): ?string
    {
        return $this->parentPath;
    }

    /**
     * @param string|null $parentPath
     */
    public function setParentPath(?string $parentPath): void
    {
        $this->parentPath = $parentPath;
    }
}
