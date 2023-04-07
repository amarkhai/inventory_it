<?php

namespace App\Domain\DataMapper\User;

use App\Domain\DataMapper\DataMapperInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\User\User;
use App\Domain\ValueObject\User\FirstNameValue;
use App\Domain\ValueObject\User\LastNameValue;
use App\Domain\ValueObject\User\UserNameValue;
use App\Domain\ValueObject\User\PasswordHashValue;
use Ramsey\Uuid\Rfc4122\UuidV4;

class UserDataMapper implements DataMapperInterface
{
    /**
     * @param array $row
     * @return User
     * @throws DomainWrongEntityParamException
     * @throws \Exception
     */
    public function map(array $row): User
    {
        return new User(
            Uuidv4::fromString($row['id']),
            new UserNameValue($row['username']),
            new PasswordHashValue($row['password_hash']),
            $row['first_name'] ? new FirstNameValue($row['first_name']) : null,
            $row['last_name'] ? new LastNameValue($row['last_name']) : null,
            new \DateTimeImmutable($row['created_at'])
        );
    }
}
