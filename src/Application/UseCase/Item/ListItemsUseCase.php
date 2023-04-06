<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\Response\Item\ListItemResponseDTO;
use App\Application\Mappers\Item\Response\ListItemsResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\ItemInteractor;
use App\Domain\ValueObject\Item\ItemIdValue;

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
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(ListItemsRequestDTO $dto): array
    {
        $rootItemId = $dto->getRootItemId();
        $items = $this->interactor->list(
            $dto->getRequesterid(),
            $rootItemId ? new ItemIdValue($rootItemId) : null
        );

        $this->responseMapper->setItems($items);
        return $this->responseMapper->map();
    }
}
