<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\DataMapper\User\UserDataMapper;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\User\PasswordHashValue;
use App\Domain\ValueObject\User\UserNameValue;
use Ramsey\Uuid\UuidInterface;

class PDOUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly \PDO $connection)
    {
    }

    /**
     * {@inheritdoc}
     * @throws DomainWrongEntityParamException
     */
    public function findAll(): array
    {
        $query = $this->connection->query('SELECT * FROM users;');

        if ($query === false) {
            throw new \RuntimeException('Не удалось получить список из всех пользователей');
        }

        return array_map(fn ($row) => (new UserDataMapper())->map($row), $query->fetchAll());
    }

    /**
     * {@inheritdoc}
     * @throws DomainWrongEntityParamException
     */
    public function findUserOfId(UuidInterface $id): ?User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id->toString()]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $userData ? (new UserDataMapper())->map($userData) : null;
    }

    public function save(User $user): void
    {
        $stmt = $this->connection->prepare('
            INSERT INTO public.users (id, username, password_hash, first_name, last_name, created_at)
            VALUES (:id, :username, :password_hash, :first_name, :last_name, :created_at)
        ');
        $stmt->bindValue(':id', $user->getId()->toString());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':password_hash', $user->getPasswordHash());
        $stmt->bindValue(':first_name', $user->getFirstName());
        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':created_at', $user->getCreatedAt()->format('Y-m-d H:i:s'));

        $stmt->execute();
    }
    public function delete(UuidInterface $id): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function findOneByUsername(UserNameValue $username): ?User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ? (new UserDataMapper())->map($row) : null;
    }
}
