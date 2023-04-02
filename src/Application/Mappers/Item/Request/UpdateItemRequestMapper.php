<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Request;

use App\Application\DTO\Request\Item\UpdateItemRequestDTO;
use App\Domain\Entity\Item\Item;
use App\Domain\ValueObject\Item\DescriptionValue;
use App\Domain\ValueObject\Item\IdValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\NameValue;
use App\Domain\ValueObject\Item\OwnerIdValue;
use App\Domain\ValueObject\Item\PathValue;
use App\Domain\ValueObject\Item\StatusValue;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Slim\Exception\HttpBadRequestException;

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

    public function map(): Item
    {
        $item = new Item();

        $item->setId(new IdValue($this->requestDTO->getId()));
        $item->setName(new NameValue($this->requestDTO->getName()));

        $status = $this->requestDTO->getStatus();
        $item->setStatus(new StatusValue($status ? ItemStatusEnum::from($status) : null));

        $item->setDescription(new DescriptionValue($this->requestDTO->getDescription()));
        $item->setPath(new PathValue($this->requestDTO->getPath()));

        $owner_id = $this->requestDTO->getOwnerId();
        $item->setOwnerId(new OwnerIdValue($owner_id ? Uuidv4::fromString($owner_id) : null));

        return $item;
    }
}
