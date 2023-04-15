<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Item\Item;

class ItemsWithTotalCount implements WithTotalCountInterface
{
    /**
     * @param Item[] $data
     * @param int $totalCount
     */
    public function __construct(
        readonly protected array $data,
        readonly protected int $totalCount,
    ) {
    }

    /**
     * @return Item[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}
