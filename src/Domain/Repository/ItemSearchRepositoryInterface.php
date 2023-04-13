<?php

namespace App\Domain\Repository;

use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemSearchTermValue;
use Ramsey\Uuid\UuidInterface;

interface ItemSearchRepositoryInterface
{
    /**
     * @param UuidInterface $userId
     * @param ItemSearchTermValue $termValue
     * @return ItemIdValue[]
     */
    public function searchAllForUser(UuidInterface $userId, ItemSearchTermValue $termValue): array;
}
