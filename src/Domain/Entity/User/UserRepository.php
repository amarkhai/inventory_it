<?php

declare(strict_types=1);

namespace App\Domain\Entity\User;

use Ramsey\Uuid\UuidInterface;

interface UserRepository
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
