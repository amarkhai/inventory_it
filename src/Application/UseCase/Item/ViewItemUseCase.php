<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ViewItemRequestDTO;
use App\Application\DTO\Response\Item\ViewItemResponseDTO;
use App\Application\Mappers\Item\Response\ViewItemResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Interactor\ItemInteractor;

class ViewItemUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly ViewItemResponseMapper $responseMapper,
        private readonly ItemInteractor $interactor
    ) {
    }

    /**
     * @param ViewItemRequestDTO $dto
     * @return ViewItemResponseDTO
     * @throws ItemNotFoundException
     */
    public function __invoke(ViewItemRequestDTO $dto): ViewItemResponseDTO
    {
        $item = $this->interactor->getOne(
            $dto->getUserId(),
            $dto->getItemId()
        );

        $this->responseMapper->setItem($item);
        return $this->responseMapper->map();
    }
}
