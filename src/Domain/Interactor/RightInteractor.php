<?php

namespace App\Domain\Interactor;

use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Right\Right;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\RightRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use Ramsey\Uuid\UuidInterface;

class RightInteractor
{
    public function __construct(
        protected RightRepositoryInterface $rightRepository,
        protected ItemRepositoryInterface $itemRepository,
        protected UserRepositoryInterface $userRepository
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

        return $this->rightRepository->findAllByPath($item->getPath());
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function create(
        UuidInterface $requesterId,
        Right $right
    ): bool {

        $user = $this->userRepository->findUserOfId($right->getUserId());
        if (!$user) {
            throw new DomainWrongEntityParamException('User does not exists.');
        }

        $savedRight = $this->rightRepository->findOneForUserByPath($right->getUserId(), $right->getPath());
        if ($savedRight) {
            throw new DomainWrongEntityParamException('Right for this user and item already exists.');
        }

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
        $savedRight = $this->rightRepository->findOneForUserByPath($requesterId, $right->getPath());
        if (!$savedRight) {
            throw new DomainWrongEntityParamException('Right does not exists.');
        }

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
        $item = $this->itemRepository->findOneByPath($right->getPath());
        if (!$item || !$item->getOwnerId()->equals($requesterId)) {
            throw new DomainWrongEntityParamException('User does not own this item.');
        }
    }
}
