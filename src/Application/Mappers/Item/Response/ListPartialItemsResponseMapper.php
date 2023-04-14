<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Response;

use App\Application\DTO\Response\Item\ListPartialItemsResponseDTO;
use App\Application\Mappers\MapperInterface;
use App\Domain\Entity\Item\PartialItem;

class ListPartialItemsResponseMapper implements MapperInterface
{
    /**
     * @var PartialItem[]
     */
    private array $items = [];

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }


    public function map(): array
    {
        return array_map(function ($item) {
            return new ListPartialItemsResponseDTO($item);
        }, $this->items);
    }
}
