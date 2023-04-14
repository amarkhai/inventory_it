<?php

namespace App\Domain\DataMapper\Item;

use App\Domain\DataMapper\DataMapperInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\PartialItem;
use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\ItemNameValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\Rfc4122\UuidV4;

class PartialItemDataMapper implements DataMapperInterface
{
    /**
     * @param array $row
     * @return PartialItem
     * @throws DomainWrongEntityParamException
     * @throws Exception
     */
    public function map(array $row): PartialItem
    {
        return new PartialItem(
            isset($row['id']) ? new ItemIdValue($row['id']) : null,
            isset($row['path']) ? new ItemPathValue($row['path']) : null,
            isset($row['status']) ? ItemStatusEnum::from($row['status']) : null,
            isset($row['owner_id']) ? Uuidv4::fromString($row['owner_id']) : null,
            isset($row['name']) ? new ItemNameValue($row['name']) : null,
            isset($row['description']) ? new ItemDescriptionValue($row['description']) : null,
            isset($row['created_at']) ? new DateTimeImmutable($row['created_at']) : null,
            isset($row['updated_at']) ? new DateTimeImmutable($row['updated_at']) : null,
        );
    }
}
