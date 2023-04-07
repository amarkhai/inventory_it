<?php

declare(strict_types=1);

namespace App\Domain\Entity\User;

use App\Domain\ValueObject\User\FirstNameValue;
use App\Domain\ValueObject\User\LastNameValue;
use App\Domain\ValueObject\User\UserNameValue;
use App\Domain\ValueObject\User\PasswordHashValue;
use DateTimeImmutable;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class User implements JsonSerializable
{
    public function __construct(
        private UuidInterface $id,
        private UserNameValue $username,
        private PasswordHashValue $password_hash,
        private ?FirstNameValue $first_name,
        private ?LastNameValue $last_name,
        private DateTimeImmutable $created_at
    ) {
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return UserNameValue
     */
    public function getUsername(): UserNameValue
    {
        return $this->username;
    }

    /**
     * @param UserNameValue $username
     */
    public function setUsername(UserNameValue $username): void
    {
        $this->username = $username;
    }

    /**
     * @return FirstNameValue|null
     */
    public function getFirstName(): ?FirstNameValue
    {
        return $this->first_name;
    }

    /**
     * @param FirstNameValue|null $first_name
     */
    public function setFirstName(?FirstNameValue $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return LastNameValue|null
     */
    public function getLastName(): ?LastNameValue
    {
        return $this->last_name;
    }

    /**
     * @param LastNameValue|null $last_name
     */
    public function setLastName(?LastNameValue $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeImmutable $created_at
     */
    public function setCreatedAt(DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return PasswordHashValue
     */
    public function getPasswordHash(): PasswordHashValue
    {
        return $this->password_hash;
    }

    /**
     * @param PasswordHashValue $password_hash
     */
    public function setPasswordHash(PasswordHashValue $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
