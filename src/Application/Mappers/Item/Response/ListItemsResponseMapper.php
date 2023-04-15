<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Response;

use App\Application\DTO\Response\DataWithTotalCountResponseDTO;
use App\Application\DTO\Response\Item\ListItemsResponseDTO;
use App\Application\Mappers\MapperInterface;
use App\Domain\Entity\Item\Item;

class ListItemsResponseMapper implements MapperInterface
{
    /**
     * @var Item[]
     */
    private array $items = [];
    private int $totalCount;

    /**
     * @param Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function map(): DataWithTotalCountResponseDTO
    {
        return new DataWithTotalCountResponseDTO(
            $this->getMappedItems(),
            $this->totalCount
        );
        //@todo отдавать права юзера на item
    }

    /**
     * @return ListItemsResponseDTO[]
     */
    private function getMappedItems(): array
    {
        return array_map(function ($item) {
            return new ListItemsResponseDTO(
                $item->getId()->getValue(),
                $item->getName()->getValue(),
                $item->getDescription()?->getValue(),
                $item->getPath()->getValue(),
                $item->getOwnerId()->toString(),
                $item->getCreatedAt()?->format('Y-m-d H:i:s'),
                $item->getUpdatedAt()?->format('Y-m-d H:i:s'),
            );
        }, $this->items);
    }
}
