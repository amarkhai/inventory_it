<?php

namespace App\Domain\Entity\Item;

use App\Domain\ValueObject\Item\IdValue;
use App\Domain\ValueObject\Item\JustCreatedIdValue;
use App\Domain\ValueObject\Item\PathValue;
use App\Domain\ValueObject\Item\TemporaryIdValue;
use Ramsey\Uuid\UuidInterface;

/**
 *  Соответствие временного $t_id, сгенерированного на клиенте постоянному $id,
 *  сгенерированному на сервере
 */
class JustCreatedItemMap
{
    private JustCreatedIdValue $id;
    private TemporaryIdValue $temporary_id;
    private PathValue $path;

    /**
     * @return JustCreatedIdValue
     */
    public function getId(): JustCreatedIdValue
    {
        return $this->id;
    }

    /**
     * @param JustCreatedIdValue $id
     */
    public function setId(JustCreatedIdValue $id): void
    {
        $this->id = $id;
    }

    /**
     * @return TemporaryIdValue
     */
    public function getTemporaryId(): TemporaryIdValue
    {
        return $this->temporary_id;
    }

    /**
     * @param TemporaryIdValue $temporary_id
     */
    public function setTemporaryId(TemporaryIdValue $temporary_id): void
    {
        $this->temporary_id = $temporary_id;
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

}
