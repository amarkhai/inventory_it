<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\Response\Item\ListItemResponseDTO;
use App\Application\Mappers\Item\Response\ListItemsResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\Interactor\ItemInteractor;

class ListItemsUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly ListItemsResponseMapper $responseMapper,
        private readonly ItemInteractor $interactor
    ) {
    }

    /**
     * @param ListItemsRequestDTO $dto
     * @return ListItemResponseDTO[]
     */
    public function __invoke(ListItemsRequestDTO $dto): array
    {
        $items = $this->interactor->list(
            $dto->getUserId(),
            $dto->getRootItemId()
        );

        $this->responseMapper->setItems($items);
        return $this->responseMapper->map();
    }
}
