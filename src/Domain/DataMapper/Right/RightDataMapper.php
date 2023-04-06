<?php

namespace App\Domain\DataMapper\Right;

use App\Domain\DataMapper\DataMapperInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Right\Right;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Right\RightTypeEnum;
use Ramsey\Uuid\Rfc4122\UuidV4;

class RightDataMapper implements DataMapperInterface
{
    /**
     * @param array $row
     * @return Right
     * @throws DomainWrongEntityParamException
     */
    public function map(array $row): Right
    {
        return new Right(
            Uuidv4::fromString($row['id']),
            new ItemIdValue($row['item_id']),
            Uuidv4::fromString($row['user_id']),
            RightTypeEnum::from($row['type']),
        );
    }
}
