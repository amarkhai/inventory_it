<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\Response\Item\ListItemResponseDTO;
use App\Application\Mappers\Item\Response\ListItemsResponseMapper;
use App\Domain\Entity\Item\ItemRepository;

class ListItemsUseCase extends ItemUseCase
{
    private ListItemsResponseMapper $mapper;

    /**
     * @param ItemRepository $itemRepository
     * @param ListItemsResponseMapper $mapper
     */
    public function __construct(
        ItemRepository $itemRepository,
        ListItemsResponseMapper $mapper
    ) {
        parent::__construct($itemRepository);
        $this->mapper = $mapper;
    }

    /**
     * @param ListItemsRequestDTO $dto
     * @return ListItemResponseDTO[]
     */
    public function __invoke(ListItemsRequestDTO $dto): array
    {
        $items = $this->itemRepository->findAllForUser(
            $dto->getUserId(),
            $dto->getRootItemId()
        );

        $this->mapper->setItems($items);
        return $this->mapper->map();
    }
}
