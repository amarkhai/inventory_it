<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\CreateItemRequestDTO;
use App\Application\DTO\Response\Item\CreateItemResponseDTO;
use App\Application\Mappers\Item\Request\CreateItemRequestMapper;
use App\Application\Mappers\Item\Response\CreateItemResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\Interactor\ItemInteractor;
use App\Domain\Interactor\Item\UpdateItemInteractor;

class CreateItemUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly CreateItemResponseMapper $responseMapper,
        private readonly CreateItemRequestMapper $requestMapper,
        private readonly ItemInteractor $interactor
    ) {
    }

    /**
     * @param CreateItemRequestDTO $dto
     * @return CreateItemResponseDTO
     */
    public function __invoke(CreateItemRequestDTO $dto): CreateItemResponseDTO
    {
        $this->requestMapper->setRequestDto($dto);
        $item = $this->requestMapper->map();

        $itemMap = $this->interactor->create(
            $item,
            $dto->getTemporaryId(),
            $dto->getParentPath()
        );

        $this->responseMapper->setItemMap($itemMap);
        return $this->responseMapper->map();
    }
}
