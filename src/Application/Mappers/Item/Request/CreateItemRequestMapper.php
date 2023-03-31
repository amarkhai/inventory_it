<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Request;

use App\Application\DTO\Request\Item\CreateItemRequestDTO;
use App\Application\DTO\Request\RequestDTO;
use App\Domain\Entity\Item\Item;
use App\Domain\ValueObject\Item\DescriptionValue;
use App\Domain\ValueObject\Item\IdValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\NameValue;
use App\Domain\ValueObject\Item\OwnerIdValue;
use App\Domain\ValueObject\Item\PathValue;
use App\Domain\ValueObject\Item\StatusValue;
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

    public function map(): Item
    {
        $item = new Item();
        $item->setName(new NameValue($this->requestDTO->getName()));
        $item->setStatus(new StatusValue(ItemStatusEnum::active));
        $item->setDescription(new DescriptionValue($this->requestDTO->getDescription()));
        $item->setOwnerId(new OwnerIdValue(Uuidv4::fromString($this->requestDTO->getOwnerId())));
        return $item;
    }
}
