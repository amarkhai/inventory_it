<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Request;

use App\Application\DTO\Request\Item\UpdateItemRequestDTO;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\ItemNameValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\Rfc4122\UuidV4;

class UpdateItemRequestMapper implements RequestMapperInterface
{
    private UpdateItemRequestDTO $requestDTO;

    /**
     * @param UpdateItemRequestDTO $requestDTO
     * @return void
     */
    public function setRequestDto(UpdateItemRequestDTO $requestDTO): void
    {
        $this->requestDTO = $requestDTO;
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function map(): Item
    {
        return new Item(
            new ItemIdValue($this->requestDTO->getId()),
            new ItemPathValue($this->requestDTO->getPath()),
            ItemStatusEnum::from($this->requestDTO->getStatus()),
            Uuidv4::fromString($this->requestDTO->getOwnerId()),
            new ItemNameValue($this->requestDTO->getName()),
            new ItemDescriptionValue($this->requestDTO->getDescription())
        );
    }
}
