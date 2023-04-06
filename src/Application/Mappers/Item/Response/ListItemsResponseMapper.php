<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Response;

use App\Application\DTO\Response\Item\ListItemResponseDTO;
use App\Application\Mappers\MapperInterface;
use App\Domain\Entity\Item\Item;

class ListItemsResponseMapper implements MapperInterface
{
    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * @param Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function map(): array
    {
        return array_map(function ($item) {
            return new ListItemResponseDTO(
                $item->getId()->getValue(),
                $item->getName()->getValue(),
                $item->getDescription()?->getValue(),
                $item->getPath()->getValue(),
            );
        }, $this->items);
    }
}
