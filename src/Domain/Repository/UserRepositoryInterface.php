<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserNotFoundException;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @throws UserNotFoundException
     */
    public function findUserOfId(UuidInterface $id): User;

    public function save(User $user): void;

    public function delete(UuidInterface $id): void;
}
