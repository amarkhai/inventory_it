<?php

namespace App\Application\UseCase\Item;

use App\Application\DTO\Request\Item\CreateItemRequestDTO;
use App\Application\DTO\Response\Item\CreateItemResponseDTO;
use App\Application\Mappers\Item\Request\CreateItemRequestMapper;
use App\Application\Mappers\Item\Response\CreateItemResponseMapper;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\ItemRepository;

class CreateItemUseCase extends ItemUseCase
{
    private CreateItemResponseMapper $responseMapper;
    private CreateItemRequestMapper $requestMapper;

    public function __construct(
        ItemRepository $itemRepository,
        CreateItemResponseMapper $responseMapper,
        CreateItemRequestMapper $requestMapper,
    ) {
        parent::__construct($itemRepository);
        $this->responseMapper = $responseMapper;
        $this->requestMapper = $requestMapper;
    }

    /**
     * @param CreateItemRequestDTO $dto
     * @return CreateItemResponseDTO
     */
    public function __invoke(CreateItemRequestDTO $dto): CreateItemResponseDTO
    {
        $this->requestMapper->setRequestDto($dto);
        $item = $this->requestMapper->map();

        $itemMap = $this->itemRepository->insert(
            $item,
            $dto->getTemporaryId(),
            $dto->getParentPath()
        );

        $this->responseMapper->setItemMap($itemMap);
        return $this->responseMapper->map();
    }
}
