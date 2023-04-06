<?php

namespace App\Application\UseCase\Right;

use App\Application\DTO\Request\Right\CreateRightRequestDTO;
use App\Application\DTO\Response\Right\CreateRightResponseDTO;
use App\Application\Mappers\Right\Request\CreateRightRequestMapper;
use App\Application\Mappers\Right\Response\CreateRightResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\RightInteractor;

class CreateRightUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly CreateRightResponseMapper $responseMapper,
        private readonly CreateRightRequestMapper $requestMapper,
        private readonly RightInteractor $interactor
    ) {
    }

    /**
     * @param CreateRightRequestDTO $dto
     * @return CreateRightResponseDTO
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(CreateRightRequestDTO $dto): CreateRightResponseDTO
    {
        $this->requestMapper->setRequestDto($dto);
        $right = $this->requestMapper->map();

        $result = $this->interactor->create(
            $dto->getRequesterId(),
            $right
        );

        $this->responseMapper->setResult($result);
        return $this->responseMapper->map();
    }
}
