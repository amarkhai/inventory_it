<?php

namespace App\Domain\Interactor;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Right\Right;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\RightRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use Ramsey\Uuid\UuidInterface;

class RightInteractor
{
    public function __construct(
        protected RightRepositoryInterface $rightRepository,
        protected ItemRepositoryInterface $itemRepository
    ) {
    }

    /**
     * @param UuidInterface $requesterId
     * @param ItemIdValue $itemId
     * @return Right[]
     * @throws DomainWrongEntityParamException
     */
    public function listForUserByItem(
        UuidInterface $requesterId,
        ItemIdValue $itemId
    ): array {
        $item = $this->itemRepository->findOneById($itemId);

        if (!$item || !$item->getOwnerId()->equals($requesterId)) {
            throw new DomainWrongEntityParamException('User does not own this item.');
        }

        return $this->rightRepository->findAllByItemId($itemId);
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function create(
        UuidInterface $requesterId,
        Right $right
    ): bool {
        //@todo check user fk
        //@todo set (update if exists)

        $this->checkCanManageRight($requesterId, $right);
        return $this->rightRepository->insert($right);
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function update(
        UuidInterface $requesterId,
        Right $right
    ): bool {
        //@todo выдавать ошибку при отсутствии right(item too)

        $this->checkCanManageRight($requesterId, $right);
        return $this->rightRepository->update($right);
    }


    /**
     * @throws DomainWrongEntityParamException
     */
    public function deleteById(
        UuidInterface $requesterId,
        UuidInterface $rightId
    ): bool {
        $right = $this->rightRepository->findOneById($rightId);
        if (!$right) {
            throw new DomainWrongEntityParamException('Right not found.');
        }
        $this->checkCanManageRight($requesterId, $right);
        return $this->rightRepository->delete($right);
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    private function checkCanManageRight(
        UuidInterface $requesterId,
        Right $right
    ): void {
        $item = $this->itemRepository->findOneById($right->getItemId());
        if (!$item || !$item->getOwnerId()->equals($requesterId)) {
            throw new DomainWrongEntityParamException('User does not own this item.');
        }
    }
}
