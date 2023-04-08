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
     * @return Item[]
     */
    public function findAll(): array;

    /**
     * @param UuidInterface $userId
     * @param ItemPathValue|null $rootItemPath
     * @return array
     */
    public function findAllForUser(UuidInterface $userId, ?ItemPathValue $rootItemPath = null): array;

    /**
     * @param UuidInterface $userId
     * @param ItemIdValue $itemId
     * @return Item|null
     */
    public function findOneForUserById(UuidInterface $userId, ItemIdValue $itemId): ?Item;

    /**
     * @param UuidInterface $userId
     * @param string $term
     * @return Item[]
     */
    public function findAllForUserByTerm(UuidInterface $userId, string $term): array;

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
