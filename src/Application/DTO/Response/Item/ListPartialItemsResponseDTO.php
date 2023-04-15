<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Item;

use App\Application\DTO\Response\ResponseDTO;
use App\Domain\Entity\Item\PartialItem;

class ListPartialItemsResponseDTO extends ResponseDTO
{
    public function __construct(
        private readonly PartialItem $item,
    ) {
    }

    /**
     * @return PartialItem
     */
    public function getItem(): PartialItem
    {
        return $this->item;
    }

    public function jsonSerialize(): array
    {
        $response = [];

        if ($id = $this->item->getId()) {
            $response['id'] = $id->getValue();
        }
        if ($path = $this->item->getPath()) {
            $response['path'] = $path->getValue();
        }
        if ($name = $this->item->getName()) {
            $response['name'] = $name->getValue();
        }
        if ($description = $this->item->getDescription()) {
            $response['description'] = $description->getValue();
        }

        return $response;
    }
}
