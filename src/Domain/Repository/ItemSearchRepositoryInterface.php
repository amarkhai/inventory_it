<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Item\PartialItem;
use App\Domain\ValueObject\Item\ItemSearchTermValue;
use Ramsey\Uuid\UuidInterface;

interface ItemSearchRepositoryInterface
{
    /**
     * @param UuidInterface $userId
     * @param ItemSearchTermValue $termValue
     * @return PartialItem[]
     */
    public function searchAllForUser(UuidInterface $userId, ItemSearchTermValue $termValue): array;
}
