<?php

namespace App\Application\UseCase\Right;

use App\Application\DTO\Request\Right\ListRightsByItemRequestDTO;
use App\Application\DTO\Response\Right\ListRightResponseDTO;
use App\Application\Mappers\Right\Response\ListRightsResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\RightInteractor;
use App\Domain\ValueObject\Item\ItemIdValue;

class ListRightsByItemUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly ListRightsResponseMapper $responseMapper,
        private readonly RightInteractor $interactor
    ) {
    }

    /**
     * @param ListRightsByItemRequestDTO $dto
     * @return ListRightResponseDTO[]
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(ListRightsByItemRequestDTO $dto): array
    {
        $rights = $this->interactor->listForUserByItem(
            $dto->getRequesterid(),
            new ItemIdValue($dto->getItemId())
        );

        $this->responseMapper->setRights($rights);
        return $this->responseMapper->map();
    }
}
