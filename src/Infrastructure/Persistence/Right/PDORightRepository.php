<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Right;

use App\Domain\DataMapper\Right\RightDataMapper;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Right\Right;
use App\Domain\Repository\RightRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\UuidInterface;

class PDORightRepository implements RightRepositoryInterface
{
    public function __construct(
        private \PDO $connection
    ) {
    }

    /**
     * @inheritDoc
     */
    public function insert(Right $right): bool
    {
        $stmt = $this->connection->prepare('
            INSERT INTO public.rights (id, path, user_id, type)
            VALUES (:id, :path, :user_id, :type)
        ');
        $stmt->bindValue(':id', $right->getId());
        $stmt->bindValue(':path', $right->getPath());
        $stmt->bindValue(':user_id', $right->getUserId());
        $stmt->bindValue(':type', $right->getType()->getValue());
        return $stmt->execute();
    }

    public function update(Right $right): bool
    {
        //@todo сделать обновление конкретных полей, чтобы не переписывалась вся сущность
        $stmt = $this->connection->prepare('
            UPDATE rights SET        
                path=:path,
                user_id=:user_id,
                type=:type
            WHERE id=:id
        ');

        $stmt->bindValue(':path', $right->getPath());
        $stmt->bindValue(':user_id', $right->getUserId());
        $stmt->bindValue(':type', $right->getType()->getValue());
        $stmt->bindValue(':id', $right->getId()->toString());

        return $stmt->execute();
    }

    /**
     * @param ItemPathValue $path
     * @return Right[]
     * @throws DomainWrongEntityParamException
     */
    public function findAllByPath(ItemPathValue $path): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM rights WHERE path=:path');
        $stmt->bindValue(':path', $path->getValue());
        $stmt->execute();
        return array_map(fn ($row) => (new RightDataMapper())->map($row), $stmt->fetchAll());
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function findOneById(UuidInterface $id): ?Right
    {
        $stmt = $this->connection->prepare('SELECT * FROM rights WHERE id = ?');
        $stmt->execute([$id]);
        $right = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $right ? (new RightDataMapper())->map($right) : null;
    }

    public function delete(Right $right): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM rights WHERE id=:id');
        $stmt->bindValue(':id', $right->getId());
        return $stmt->execute();
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function findOneForUserByPath(
        UuidInterface $userId,
        ItemPathValue $path
    ): ?Right {
        $stmt = $this->connection->prepare('
                SELECT * FROM rights 
                WHERE user_id = :user_id
                    AND path = :path
            ');
        $stmt->bindValue(':user_id', $userId->toString());
        $stmt->bindValue(':path', $path->getValue());
        $stmt->execute();
        $right = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $right ? (new RightDataMapper())->map($right) : null;
    }
}
