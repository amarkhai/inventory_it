<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ViewItemRequestDTO;
use App\Application\DTO\Response\Item\ViewItemResponseDTO;
use App\Application\Mappers\Item\Response\ViewItemResponseMapper;
use App\Domain\Entity\Item\ItemRepository;

class ViewItemUseCase extends ItemUseCase
{
    private ViewItemResponseMapper $mapper;

    /**
     * @param ItemRepository $itemRepository
     * @param ViewItemResponseMapper $mapper
     */
    public function __construct(
        ItemRepository $itemRepository,
        ViewItemResponseMapper $mapper
    ) {
        parent::__construct($itemRepository);
        $this->mapper = $mapper;
    }

    /**
     * @param ViewItemRequestDTO $dto
     * @return ViewItemResponseDTO
     */
    public function __invoke(ViewItemRequestDTO $dto): ViewItemResponseDTO
    {
        $item = $this->itemRepository->findOneForUserById(
            $dto->getUserId(),
            $dto->getItemId()
        );

        $this->mapper->setItem($item);
        return $this->mapper->map();
    }
}
