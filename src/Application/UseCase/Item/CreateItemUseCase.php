<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\CreateItemRequestDTO;
use App\Application\DTO\Response\Item\CreateItemResponseDTO;
use App\Application\Mappers\Item\Request\CreateItemRequestMapper;
use App\Application\Mappers\Item\Response\CreateItemResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\ItemInteractor;
use App\Domain\Interactor\Item\UpdateItemInteractor;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;

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
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(CreateItemRequestDTO $dto): CreateItemResponseDTO
    {
        $this->requestMapper->setRequestDto($dto);
        $item = $this->requestMapper->map();

        $parentPath = $dto->getParentPath();
        $itemMap = $this->interactor->create(
            $item,
            UuidV4::fromString($dto->getTemporaryId()),
            $parentPath ? new ItemPathValue($parentPath) : null
        );

        $this->responseMapper->setItemMap($itemMap);
        return $this->responseMapper->map();
    }
}
