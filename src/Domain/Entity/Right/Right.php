<?php

namespace App\Domain\Entity\Right;

use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Right\RightTypeEnum;
use Ramsey\Uuid\UuidInterface;

class Right
{
    public function __construct(
        private UuidInterface $id,
        private ItemIdValue $item_id,
        private UuidInterface $user_id,
        private RightTypeEnum $type,
    ) {
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return ItemIdValue
     */
    public function getItemId(): ItemIdValue
    {
        return $this->item_id;
    }

    /**
     * @param ItemIdValue $item_id
     */
    public function setItemId(ItemIdValue $item_id): void
    {
        $this->item_id = $item_id;
    }

    /**
     * @return UuidInterface
     */
    public function getUserId(): UuidInterface
    {
        return $this->user_id;
    }

    /**
     * @param UuidInterface $user_id
     */
    public function setUserId(UuidInterface $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return RightTypeEnum
     */
    public function getType(): RightTypeEnum
    {
        return $this->type;
    }

    /**
     * @param RightTypeEnum $type
     */
    public function setType(RightTypeEnum $type): void
    {
        $this->type = $type;
    }
}
