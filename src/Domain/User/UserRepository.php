<?php

declare(strict_types=1);

namespace App\Domain\User;

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
    public function findUserOfId(UuidInterface $uuid): User;

    public function save(User $user): void;

    public function delete(UuidInterface $uuid): void;
}
