<?php

namespace App\Domain\Entity\Item;

use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\UuidInterface;

/**
 *  Соответствие временного $t_id, сгенерированного на клиенте постоянному $id,
 *  сгенерированному на сервере
 */
class JustCreatedItemMap
{
    private ItemIdValue $id;
    private UuidInterface $temporary_id;
    private ItemPathValue $path;

    /**
     * @return ItemIdValue
     */
    public function getId(): ItemIdValue
    {
        return $this->id;
    }

    /**
     * @param ItemIdValue $id
     */
    public function setId(ItemIdValue $id): void
    {
        $this->id = $id;
    }

    /**
     * @return UuidInterface
     */
    public function getTemporaryId(): UuidInterface
    {
        return $this->temporary_id;
    }

    /**
     * @param UuidInterface $temporary_id
     */
    public function setTemporaryId(UuidInterface $temporary_id): void
    {
        $this->temporary_id = $temporary_id;
    }

    /**
     * @return ItemPathValue
     */
    public function getPath(): ItemPathValue
    {
        return $this->path;
    }

    /**
     * @param ItemPathValue $path
     */
    public function setPath(ItemPathValue $path): void
    {
        $this->path = $path;
    }
}
