<?php

namespace App\Domain\Interactor;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\UuidInterface;

class ItemInteractor
{
    public function __construct(
        protected ItemRepositoryInterface $itemRepository
    ) {
    }

    /**
     * @param UuidInterface $userId
     * @param ItemIdValue|null $rootItemId
     * @return Item[]
     */
    public function list(
        UuidInterface $userId,
        ?ItemIdValue $rootItemId
    ): array {
        return $this->itemRepository->findAllForUser(
            $userId,
            $rootItemId
        );
    }

    /**
     * @param UuidInterface $userId
     * @param ItemIdValue $itemId
     * @return Item
     * @throws ItemNotFoundException
     */
    public function getOne(
        UuidInterface $userId,
        ItemIdValue $itemId
    ): Item {

        $item = $this->itemRepository->findOneForUserById(
            $userId,
            $itemId
        );

        if (!$item) {
            throw new ItemNotFoundException();
        }

        return $item;
    }

    public function create(
        Item $item,
        UuidInterface $temporaryId,
        ?ItemPathValue $parentPath
    ): JustCreatedItemMap {
        //@todo чекать права на создание в $parentPath
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
        //@todo чекать права на изменение объекта
        if (!$item->getId()) {
            throw new DomainWrongEntityParamException('Item id value must not be null');
        }

        return $this->itemRepository->update($item);
    }
}
