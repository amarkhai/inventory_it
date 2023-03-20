<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class User implements JsonSerializable
{
    private UuidInterface $uuid;

    private string $username;

    private string $password;

    private ?string $firstName = null;

    private ?string $lastName = null;

    private \DateTimeImmutable $createdAt;

    public function __construct(UuidInterface $uuid, string $username, string $password, \DateTimeImmutable $createdAt)
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }

    public function updateFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function updateLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
