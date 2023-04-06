<?php

namespace App\Application\UseCase\Right;

use App\Application\DTO\Request\Right\DeleteRightRequestDTO;
use App\Application\DTO\Response\Right\DeleteRightResponseDTO;
use App\Application\Mappers\Right\Response\DeleteRightResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Interactor\RightInteractor;
use Ramsey\Uuid\Rfc4122\UuidV4;

class DeleteRightUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly DeleteRightResponseMapper $responseMapper,
        private readonly RightInteractor $interactor
    ) {
    }

    /**
     * @param DeleteRightRequestDTO $dto
     * @return DeleteRightResponseDTO
     * @throws DomainWrongEntityParamException
     */
    public function __invoke(DeleteRightRequestDTO $dto): DeleteRightResponseDTO
    {
        $result = $this->interactor->deleteById(
            $dto->getRequesterId(),
            UuidV4::fromString($dto->getId())
        );

        $this->responseMapper->setResult($result);
        return $this->responseMapper->map();
    }
}
