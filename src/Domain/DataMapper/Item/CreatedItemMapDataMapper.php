<?php

namespace App\Domain\DataMapper\Item;

use App\Domain\DataMapper\DataMapperInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\Rfc4122\UuidV4;

class CreatedItemMapDataMapper implements DataMapperInterface
{
    /**
     * @param array $row
     * @return JustCreatedItemMap
     * @throws DomainWrongEntityParamException
     */
    public function map(array $row): JustCreatedItemMap
    {
        $createdItemMap = new JustCreatedItemMap();
        $createdItemMap->setId(new ItemIdValue($row['id']));
        $createdItemMap->setPath(new ItemPathValue($row['path']));
        $createdItemMap->setTemporaryId(Uuidv4::fromString($row['temporary_id']));
        return $createdItemMap;
    }
}
