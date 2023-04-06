<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Request;

use App\Application\DTO\Request\Item\CreateItemRequestDTO;
use App\Application\DTO\Request\RequestDTO;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\ItemNameValue;
use Ramsey\Uuid\Rfc4122\UuidV4;

class CreateItemRequestMapper implements RequestMapperInterface
{
    private CreateItemRequestDTO $requestDTO;

    /**
     * @param CreateItemRequestDTO $requestDTO
     * @return void
     */
    public function setRequestDto(CreateItemRequestDTO $requestDTO): void
    {
        $this->requestDTO = $requestDTO;
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function map(): Item
    {
        return new Item(
            null,
            null,
            ItemStatusEnum::active,
            Uuidv4::fromString($this->requestDTO->getOwnerId()),
            new ItemNameValue($this->requestDTO->getName()),
            new ItemDescriptionValue($this->requestDTO->getDescription())
        );
    }
}
