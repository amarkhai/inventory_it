<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\Response\Item\ListItemResponseDTO;
use App\Application\Mappers\Item\Response\ListItemsResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\ItemInteractor;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;

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
        $rootItemPath = $dto->getRootItemPath();
        $items = $this->interactor->listAvailableForUser(
            $dto->getRequesterid(),
            $rootItemPath ? new ItemPathValue($rootItemPath) : null
        );

        $this->responseMapper->setItems($items);
        return $this->responseMapper->map();
    }
}
