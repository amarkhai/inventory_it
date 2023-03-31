<?php

declare(strict_types=1);

namespace App\Domain\Entity\Item;

use Ramsey\Uuid\UuidInterface;

interface ItemRepository
{
    /**
     * @return Item[]
     */
    public function findAll(): array;
    /**
     * @param UuidInterface $userId
     * @param int|null $rootItemId
     * @return Item[]
     */
    public function findAllForUser(UuidInterface $userId, ?int $rootItemId = null): array;

    /**
     * @param UuidInterface $userId
     * @param int $itemId
     * @return Item
     * @throws ItemNotFoundException
     */
    public function findOneForUserById(UuidInterface $userId, int $itemId): Item;

    /**
     * @param UuidInterface $userId
     * @param string $term
     * @return Item[]
     */
    public function findAllForUserByTerm(UuidInterface $userId, string $term): array;

    /**
     * @param Item $item
     * @param string $temporaryId
     * @param string|null $parentPath
     * @return JustCreatedItemMap
     */
    public function insert(
        Item $item,
        string $temporaryId,
        ?string $parentPath
    ): JustCreatedItemMap;

    /**
     * @param Item $item
     * @return bool
     */
    public function update(Item $item): bool;

}
