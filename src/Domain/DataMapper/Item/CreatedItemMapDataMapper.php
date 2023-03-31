<?php

namespace App\Domain\DataMapper\Item;

use App\Domain\DataMapper\DataMapperInterface;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\ValueObject\Item\JustCreatedIdValue;
use App\Domain\ValueObject\Item\PathValue;
use App\Domain\ValueObject\Item\TemporaryIdValue;
use Ramsey\Uuid\Rfc4122\UuidV4;

class CreatedItemMapDataMapper implements DataMapperInterface
{
    /**
     * @param array $row
     * @return JustCreatedItemMap
     */
    public function map(array $row): JustCreatedItemMap
    {
        $createdItemMap = new JustCreatedItemMap();
        $createdItemMap->setId(new JustCreatedIdValue($row['id']));
        $createdItemMap->setPath(new PathValue($row['path']));
        $createdItemMap->setTemporaryId(new TemporaryIdValue($row['temporary_id']));
        return $createdItemMap;
    }
}
