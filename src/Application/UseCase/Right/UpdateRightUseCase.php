<?php

namespace App\Application\UseCase\Right;

use App\Application\DTO\Request\Right\UpdateRightRequestDTO;
use App\Application\DTO\Response\Right\UpdateRightResponseDTO;
use App\Application\Mappers\Right\Request\UpdateRightRequestMapper;
use App\Application\Mappers\Right\Response\UpdateRightResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\RightInteractor;

class UpdateRightUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly UpdateRightResponseMapper $responseMapper,
        private readonly UpdateRightRequestMapper $requestMapper,
        private readonly RightInteractor $interactor
    ) {
    }

    /**
     * @param UpdateRightRequestDTO $dto
     * @return UpdateRightResponseDTO
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(UpdateRightRequestDTO $dto): UpdateRightResponseDTO
    {
        $this->requestMapper->setRequestDto($dto);
        $right = $this->requestMapper->map();

        $result = $this->interactor->update(
            $dto->getRequesterId(),
            $right
        );

        $this->responseMapper->setResult($result);
        return $this->responseMapper->map();
    }
}
