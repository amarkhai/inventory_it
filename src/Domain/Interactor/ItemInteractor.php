<?php

namespace App\Domain\Interactor;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\ItemSearchRepositoryInterface;
use App\Domain\Repository\RightRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use App\Domain\ValueObject\Item\ItemSearchTermValue;
use App\Domain\ValueObject\Right\RightTypeEnum;
use Ramsey\Uuid\UuidInterface;

class ItemInteractor
{
    public function __construct(
        protected ItemRepositoryInterface $itemRepository,
        protected ItemSearchRepositoryInterface $itemSearchRepository,
        protected RightRepositoryInterface $rightRepository
    ) {
    }

    /**
     * @param UuidInterface $userId
     * @param ItemPathValue|null $rootItemPath
     * @return Item[]
     */
    public function listAvailableForUser(
        UuidInterface $userId,
        ?ItemPathValue $rootItemPath
    ): array {
        return $this->itemRepository->findAllForUser(
            $userId,
            $rootItemPath
        );
    }

    /**
     * @param UuidInterface $userId
     * @param ItemSearchTermValue $term
     * @return ItemIdValue[]
     */
    public function searchAvailableForUser(
        UuidInterface $userId,
        ItemSearchTermValue $term
    ): array {
        return $this->itemSearchRepository->searchAllForUser($userId, $term);
    }

    /**
     * @param UuidInterface $userId
     * @param ItemIdValue $itemId
     * @return Item
     * @throws ItemNotFoundException
     */
    public function getOneAvailableForUser(
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

    /**
     * @throws DomainWrongEntityParamException
     */
    public function create(
        UuidInterface $requesterId,
        Item $item,
        UuidInterface $temporaryId,
        ?ItemPathValue $parentPath
    ): JustCreatedItemMap {

        if ($parentPath) {
            $parentItem = $this->itemRepository->findOneByPath($parentPath);
            if (!$parentItem) {
                throw new DomainWrongEntityParamException('Item with this path does not exists.');
            }
            $this->checkCanManageItem($requesterId, $parentItem);
        }

        return $this->itemRepository->insert(
            $item,
            $temporaryId,
            $parentPath
        );
    }

    /**
     * @param UuidInterface $requesterId
     * @param Item $item
     * @return bool
     * @throws DomainWrongEntityParamException
     */
    public function update(
        UuidInterface $requesterId,
        Item $item
    ): bool {

        if (!$item->getId()) {
            throw new DomainWrongEntityParamException('Item id value must not be null');
        }

        $savedItem = $this->itemRepository->findOneById($item->getId());
        if (!$savedItem) {
            throw new DomainWrongEntityParamException('Item does not exists.');
        }

        // проверка прав на изменение item, в которую перемещается эта item
        if ($savedItem->getPath()->getValue() !== $item->getPath()->getValue()) {
            //@todo сделать проверку
        }

        $this->checkCanManageItem($requesterId, $savedItem);

        return $this->itemRepository->update($item);
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    private function checkCanManageItem(
        UuidInterface $requesterId,
        Item $item
    ): void {
        if (!$item->getOwnerId()->equals($requesterId)) {
            $savedRight = $this->rightRepository->findOneForUserByPath($requesterId, $item->getPath());
            if (!$savedRight || $savedRight->getType() !== RightTypeEnum::rw) {
                throw new DomainWrongEntityParamException('Item update prohibited for you.');
            }
        }
    }
}
