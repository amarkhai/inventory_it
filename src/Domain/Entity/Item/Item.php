<?php

declare(strict_types=1);

namespace App\Domain\Entity\Item;

use App\Domain\ValueObject\Item\DescriptionValue;
use App\Domain\ValueObject\Item\IdValue;
use App\Domain\ValueObject\Item\NameValue;
use App\Domain\ValueObject\Item\OwnerIdValue;
use App\Domain\ValueObject\Item\PathValue;
use App\Domain\ValueObject\Item\StatusValue;

class Item
{
    private IdValue $id;
    private PathValue $path;
    private StatusValue $status;
    private OwnerIdValue $owner_id;
    private NameValue $name;
    private DescriptionValue $description;

    /**
     * @return IdValue
     */
    public function getId(): IdValue
    {
        return $this->id;
    }

    /**
     * @param IdValue $id
     */
    public function setId(IdValue $id): void
    {
        $this->id = $id;
    }

    /**
     * @return PathValue
     */
    public function getPath(): PathValue
    {
        return $this->path;
    }

    /**
     * @param PathValue $path
     */
    public function setPath(PathValue $path): void
    {
        $this->path = $path;
    }

    /**
     * @return StatusValue
     */
    public function getStatus(): StatusValue
    {
        return $this->status;
    }

    /**
     * @param StatusValue $status
     */
    public function setStatus(StatusValue $status): void
    {
        $this->status = $status;
    }

    /**
     * @return OwnerIdValue
     */
    public function getOwnerId(): OwnerIdValue
    {
        return $this->owner_id;
    }

    /**
     * @param OwnerIdValue $owner_id
     */
    public function setOwnerId(OwnerIdValue $owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return NameValue
     */
    public function getName(): NameValue
    {
        return $this->name;
    }

    /**
     * @param NameValue $name
     */
    public function setName(NameValue $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DescriptionValue
     */
    public function getDescription(): DescriptionValue
    {
        return $this->description;
    }

    /**
     * @param DescriptionValue $description
     */
    public function setDescription(DescriptionValue $description): void
    {
        $this->description = $description;
    }
}
