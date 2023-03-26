<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PDOUserRepository implements UserRepository
{
    public function __construct(private readonly \PDO $connection)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $users = $this->connection->query('SELECT * FROM users;')->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function (array $item) {
            $user = new User(
                Uuid::fromString($item['id']),
                $item['username'],
                $item['password'],
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:sp', $item['created_at'])
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

        $user = new User(
            Uuid::fromString($userData['id']),
            $userData['username'],
            $userData['password'],
            \DateTimeImmutable::createFromFormat('Y-m-d H:i:sp', $userData['created_at'])
        );
        $user->updateFirstName($userData['first_name']);
        $user->updateLastName($userData['last_name']);

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
