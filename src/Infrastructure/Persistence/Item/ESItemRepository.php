<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Item;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Repository\ItemSearchRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemSearchTermValue;
use Ramsey\Uuid\UuidInterface;

class ESItemRepository implements ItemSearchRepositoryInterface
{
    /**
     * @inheritDoc
     * @throws DomainWrongEntityParamException
     */
    public function searchAllForUser(UuidInterface $userId, ItemSearchTermValue $termValue): array
    {

        //@todo implement
        return [
            new ItemIdValue(123),
            new ItemIdValue(321)
        ];
    }
}
