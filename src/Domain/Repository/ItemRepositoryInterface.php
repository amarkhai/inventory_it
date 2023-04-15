<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\UuidInterface;

interface ItemRepositoryInterface
{
    /**
     * @param UuidInterface $userId
     * @param ItemPathValue|null $rootItemPath
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function findAllForUser(
        UuidInterface $userId,
        ?ItemPathValue $rootItemPath = null,
        int $offset = 0,
        int $limit = 20
    ): array;

    /**
     * @param UuidInterface $userId
     * @param ItemPathValue|null $rootItemPath
     * @return int
     */
    public function countAllForUser(
        UuidInterface $userId,
        ?ItemPathValue $rootItemPath = null
    ): int;

    /**
     * @param UuidInterface $userId
     * @param ItemIdValue $itemId
     * @return Item|null
     */
    public function findOneForUserById(UuidInterface $userId, ItemIdValue $itemId): ?Item;

    public function insert(
        Item $item,
        UuidInterface $temporaryId,
        ?ItemPathValue $parentPath
    ): JustCreatedItemMap;

    /**
     * @param Item $item
     * @return bool
     */
    public function update(Item $item): bool;

    public function findOneById(ItemIdValue $id): ?Item;
    public function findOneByPath(ItemPathValue $path): ?Item;
}
