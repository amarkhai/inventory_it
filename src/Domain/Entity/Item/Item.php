<?php

declare(strict_types=1);

namespace App\Domain\Entity\Item;

use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemNameValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Right\RightTypeEnum;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class Item
{
    public function __construct(
        private ?ItemIdValue $id,
        private ?ItemPathValue $path,
        private ItemStatusEnum $status,
        private UuidInterface $owner_id,
        private ItemNameValue $name,
        private ?ItemDescriptionValue $description,
        private ?DateTimeImmutable $created_at = null,
        private ?DateTimeImmutable $updated_at = null,
    ) {
    }

    /**
     * @return ItemIdValue|null
     */
    /** @psalm-ignore-nullable-return */
    public function getId(): ?ItemIdValue
    {
        return $this->id;
    }

    /**
     * @param ItemIdValue|null $id
     */
    public function setId(?ItemIdValue $id): void
    {
        $this->id = $id;
    }

    /**
     * @return ItemPathValue|null
     */
    /** @psalm-ignore-nullable-return */
    public function getPath(): ?ItemPathValue
    {
        return $this->path;
    }

    /**
     * @param ItemPathValue|null $path
     */
    public function setPath(?ItemPathValue $path): void
    {
        $this->path = $path;
    }

    /**
     * @return ItemStatusEnum
     */
    public function getStatus(): ItemStatusEnum
    {
        return $this->status;
    }

    /**
     * @param ItemStatusEnum $status
     */
    public function setStatus(ItemStatusEnum $status): void
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
     * @return ItemNameValue
     */
    public function getName(): ItemNameValue
    {
        return $this->name;
    }

    /**
     * @param ItemNameValue $name
     */
    public function setName(ItemNameValue $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ItemDescriptionValue|null
     */
    public function getDescription(): ?ItemDescriptionValue
    {
        return $this->description;
    }

    /**
     * @param ItemDescriptionValue|null $description
     */
    public function setDescription(?ItemDescriptionValue $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeImmutable|null $created_at
     */
    public function setCreatedAt(?DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeImmutable|null $updated_at
     */
    public function setUpdatedAt(?DateTimeImmutable $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
