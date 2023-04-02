<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Response;

use App\Application\DTO\Response\Item\ViewItemResponseDTO;
use App\Application\Mappers\MapperInterface;
use App\Domain\Entity\Item\Item;

class ViewItemResponseMapper implements MapperInterface
{
    /**
     * @var Item
     */
    private Item $item;

    /**
     * @param Item $item
     */
    public function setItem(Item $item): void
    {
        $this->item = $item;
    }


    public function map(): ViewItemResponseDTO
    {
        return new ViewItemResponseDTO(
            (int) $this->item->getId()->getValue(),
            $this->item->getName()->getValue(),
            $this->item->getDescription()->getValue(),
            $this->item->getPath()->getValue(),
        );
    }
}
