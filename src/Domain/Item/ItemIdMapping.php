<?php

namespace App\Domain\Item;

use Ramsey\Uuid\UuidInterface;

/**
 *  Соответствие временного $t_id, сгенерированного на клиенте постоянному $id,
 *  сгенерированному на сервере
 */
class ItemIdMapping
{
    public UuidInterface $t_id;
    public ?int $id;
    public ?string $path;

    /**
     * @param UuidInterface $t_id
     */
    public function __construct(UuidInterface $t_id)
    {
        $this->t_id = $t_id;
    }

    /**
     * @param UuidInterface $t_id
     * @param int $id
     * @param string|null $path
     */
}
