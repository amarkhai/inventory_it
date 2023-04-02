<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PDOUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly \PDO $connection)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $query = $this->connection->query('SELECT * FROM users;');

        if ($query === false) {
            throw new \RuntimeException('Не удалось получить список из всех пользователей');
        }

        $users = $query->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function (array $item) {
            $createdAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:sp', $item['created_at']);
            if ($createdAt === false) {
                throw new \RuntimeException('Некорректный created_at у пользователя ' . $item['id']);
            }
            $user = new User(
                Uuid::fromString($item['id']),
                $item['username'],
                $item['password'],
                $createdAt
            );
            $user->updateFirstName($item['first_name']);
            $user->updateLastName($item['last_name']);

            return $user;
        }, $users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(UuidInterface $id): User
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id->toString()]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($userData === false) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        $createdAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:sp', (string)$userData['created_at']);
        if ($createdAt === false) {
            throw new \RuntimeException('Некорректный created_at у пользователя ' . $userData['id']);
        }

        $user = new User(
            Uuid::fromString((string)$userData['id']),
            (string)$userData['username'],
            (string)$userData['password'],
            $createdAt
        );
        $user->updateFirstName((string)$userData['first_name']);
        $user->updateLastName((string)$userData['last_name']);

        return $user;
    }

    public function save(User $user): void
    {
        $stmt = $this->connection->prepare('
            INSERT INTO public.users (id, username, password, first_name, last_name, created_at)
            VALUES (:id, :username, :password, :first_name, :last_name, :created_at)
        ');
        $stmt->bindValue(':id', $user->getId()->toString());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':first_name', $user->getFirstName());
        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':created_at', $user->getCreatedAt()->format('Y-m-d H:i:s'));

        $stmt->execute();
    }
    public function delete(UuidInterface $id): void
    {
        // TODO: Implement delete() method.
    }
}
