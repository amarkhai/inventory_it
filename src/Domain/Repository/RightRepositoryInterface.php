<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\Entity\Right\Right;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\UuidInterface;

interface RightRepositoryInterface
{
    /**
     * @param ItemPathValue $path
     * @return Right[]
     */
    public function findAllByPath(ItemPathValue $path): array;

    /**
     * @param Right $right
     * @return bool
     */
    public function insert(Right $right): bool;

    /**
     * @param Right $right
     * @return bool
     */
    public function update(Right $right): bool;

    public function delete(Right $right): bool;

    public function findOneById(UuidInterface $id): ?Right;

    public function findOneForUserByPath(
        UuidInterface $userId,
        ItemPathValue $path
    ): ?Right;
}
