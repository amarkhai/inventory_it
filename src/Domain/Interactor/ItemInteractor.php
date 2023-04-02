<?php

namespace App\Domain\Interactor;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\Repository\ItemRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class ItemInteractor
{
    public function __construct(
        protected ItemRepositoryInterface $itemRepository
    ) {
    }

    /**
     * @param UuidInterface $userId
     * @param int|null $rootItemId
     * @return Item[]
     */
    public function list(
        UuidInterface $userId,
        ?int $rootItemId = null
    ): array {

        return $this->itemRepository->findAllForUser(
            $userId,
            $rootItemId
        );
    }

    /**
     * @param UuidInterface $userId
     * @param int $itemId
     * @return Item
     * @throws ItemNotFoundException
     */
    public function getOne(
        UuidInterface $userId,
        int $itemId
    ): Item {

        return $this->itemRepository->findOneForUserById(
            $userId,
            $itemId
        );
    }

    public function create(
        Item $item,
        string $temporaryId,
        ?string $parentPath
    ): JustCreatedItemMap {

        return $this->itemRepository->insert(
            $item,
            $temporaryId,
            $parentPath
        );
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function update(Item $item): bool
    {
        if (!$item->getId()->getValue()) {
            throw new DomainWrongEntityParamException('Item id value must not be null');
        }

        return $this->itemRepository->update($item);
    }
}
