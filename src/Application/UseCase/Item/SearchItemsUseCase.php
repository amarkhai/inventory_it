<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\SearchItemsRequestDTO;
use App\Application\DTO\Response\Item\ListItemsResponseDTO;
use App\Application\Mappers\Item\Response\ListItemIdsResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\ItemInteractor;
use App\Domain\ValueObject\Item\ItemSearchTermValue;

class SearchItemsUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly ListItemIdsResponseMapper $responseMapper,
        private readonly ItemInteractor $interactor
    ) {
    }

    /**
     * @param SearchItemsRequestDTO $dto
     * @return ListItemsResponseDTO[]
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(SearchItemsRequestDTO $dto): array
    {
        $ids = $this->interactor->searchAvailableForUser(
            $dto->getRequesterid(),
            new ItemSearchTermValue($dto->getTerm())
        );

        $this->responseMapper->setIds($ids);
        return $this->responseMapper->map();
    }
}
