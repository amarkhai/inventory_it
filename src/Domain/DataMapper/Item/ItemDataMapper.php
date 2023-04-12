<?php

namespace App\Domain\DataMapper\Item;

use App\Domain\DataMapper\DataMapperInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\ItemNameValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\Rfc4122\UuidV4;

class ItemDataMapper implements DataMapperInterface
{
    /**
     * @param array $row
     * @return Item
     * @throws DomainWrongEntityParamException
     * @throws Exception
     */
    public function map(array $row): Item
    {
        return new Item(
            new ItemIdValue($row['id']),
            new ItemPathValue($row['path']),
            ItemStatusEnum::from($row['status']),
            Uuidv4::fromString($row['owner_id']),
            new ItemNameValue($row['name']),
            new ItemDescriptionValue($row['description']),
            new DateTimeImmutable($row['created_at']),
            new DateTimeImmutable($row['updated_at']),
        );
    }
}
