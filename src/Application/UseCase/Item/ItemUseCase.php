<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\RequestDTO;
use App\Domain\Entity\Item\ItemRepository;

abstract class ItemUseCase
{
    protected ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }
}
