<?php

declare(strict_types=1);

namespace App\Application\Mappers\Right\Request;

use App\Application\DTO\Request\Right\CreateRightRequestDTO;
use App\Application\Mappers\Item\Request\RequestMapperInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Right\Right;
use App\Domain\ValueObject\Item\ItemPathValue;
use App\Domain\ValueObject\Right\RightTypeEnum;
use Ramsey\Uuid\Rfc4122\UuidV4;

class CreateRightRequestMapper implements RequestMapperInterface
{
    private CreateRightRequestDTO $requestDTO;

    /**
     * @param CreateRightRequestDTO $requestDTO
     * @return void
     */
    public function setRequestDto(CreateRightRequestDTO $requestDTO): void
    {
        $this->requestDTO = $requestDTO;
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function map(): Right
    {
        return new Right(
            Uuidv4::fromString($this->requestDTO->getId()),
            new ItemPathValue($this->requestDTO->getPath()),
            Uuidv4::fromString($this->requestDTO->getUserId()),
            RightTypeEnum::from($this->requestDTO->getType())
        );
    }
}
