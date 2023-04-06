<?php

declare(strict_types=1);

namespace App\Application\Mappers\Right\Request;

use App\Application\DTO\Request\Right\CreateRightRequestDTO;
use App\Application\DTO\Request\Right\UpdateRightRequestDTO;
use App\Application\Mappers\Item\Request\RequestMapperInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Right\Right;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Right\RightTypeEnum;
use Ramsey\Uuid\Rfc4122\UuidV4;

class UpdateRightRequestMapper implements RequestMapperInterface
{
    private UpdateRightRequestDTO $requestDTO;

    /**
     * @param UpdateRightRequestDTO $requestDTO
     * @return void
     */
    public function setRequestDto(UpdateRightRequestDTO $requestDTO): void
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
            new ItemIdValue($this->requestDTO->getItemId()),
            Uuidv4::fromString($this->requestDTO->getUserId()),
            RightTypeEnum::from($this->requestDTO->getType())
        );
    }
}
