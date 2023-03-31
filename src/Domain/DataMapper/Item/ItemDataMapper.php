<?php

namespace App\Domain\DataMapper\Item;

use App\Domain\DataMapper\DataMapperInterface;
use App\Domain\Entity\Item\Item;
use App\Domain\ValueObject\Item\DescriptionValue;
use App\Domain\ValueObject\Item\IdValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\NameValue;
use App\Domain\ValueObject\Item\OwnerIdValue;
use App\Domain\ValueObject\Item\PathValue;
use App\Domain\ValueObject\Item\StatusValue;
use Ramsey\Uuid\Rfc4122\UuidV4;

class ItemDataMapper implements DataMapperInterface
{
    /**
     * @param array $row
     * @return Item
     */
    public function map(array $row): Item
    {
        $item = new Item();
        $item->setId(new IdValue($row['id']));
        $item->setPath(new PathValue($row['path']));
        $item->setName(new NameValue($row['name']));
        $item->setStatus(new StatusValue(ItemStatusEnum::from($row['status'])));
        $item->setDescription(new DescriptionValue($row['description']));
        $item->setOwnerId(new OwnerIdValue(Uuidv4::fromString($row['owner_id'])));
        return $item;
    }
}
