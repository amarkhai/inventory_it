<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\Response\DataWithTotalCountResponseDTO;
use App\Application\Mappers\Item\Response\ListItemsResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\ItemInteractor;
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
     * @return DataWithTotalCountResponseDTO
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(ListItemsRequestDTO $dto): DataWithTotalCountResponseDTO
    {
        $rootItemPath = $dto->getRootItemPath();
        $itemsOnPage = 20;
        $offset = ($dto->getPage() - 1) * $itemsOnPage;

        $itemsWithCount = $this->interactor->listAvailableForUser(
            $dto->getRequesterid(),
            $rootItemPath ? new ItemPathValue($rootItemPath) : null,
            $offset,
            $itemsOnPage
        );

        $this->responseMapper->setItems($itemsWithCount->getData());
        $this->responseMapper->setTotalCount($itemsWithCount->getTotalCount());
        return $this->responseMapper->map();
    }
}
