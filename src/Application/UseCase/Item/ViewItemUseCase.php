<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\ViewItemRequestDTO;
use App\Application\DTO\Response\Item\ViewItemResponseDTO;
use App\Application\Mappers\Item\Response\ViewItemResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Interactor\ItemInteractor;
use App\Domain\ValueObject\Item\ItemIdValue;

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
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(ViewItemRequestDTO $dto): ViewItemResponseDTO
    {
        $item = $this->interactor->getOne(
            $dto->getRequesterid(),
            new ItemIdValue($dto->getItemId())
        );

        $this->responseMapper->setItem($item);
        return $this->responseMapper->map();
    }
}
