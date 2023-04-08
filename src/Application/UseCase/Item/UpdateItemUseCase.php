<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\UpdateItemRequestDTO;
use App\Application\DTO\Response\Item\UpdateItemResponseDTO;
use App\Application\Mappers\Item\Request\UpdateItemRequestMapper;
use App\Application\Mappers\Item\Response\UpdateItemResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\ItemInteractor;

class UpdateItemUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly UpdateItemResponseMapper $responseMapper,
        private readonly UpdateItemRequestMapper $requestMapper,
        private readonly ItemInteractor $interactor
    ) {
    }

    /**
     * @param UpdateItemRequestDTO $dto
     * @return UpdateItemResponseDTO
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(UpdateItemRequestDTO $dto): UpdateItemResponseDTO
    {
        $this->requestMapper->setRequestDto($dto);
        $item = $this->requestMapper->map();

        $result = $this->interactor->update(
            $dto->getRequesterId(),
            $item
        );

        $this->responseMapper->setResult($result);
        return $this->responseMapper->map();
    }
}
