<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Right;

use App\Domain\DataMapper\Right\RightDataMapper;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Right\Right;
use App\Domain\Repository\RightRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
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
            INSERT INTO public.rights (id, item_id, user_id, type)
            VALUES (:id, :item_id, :user_id, :type)
        ');
        $stmt->bindValue(':id', $right->getId());
        $stmt->bindValue(':item_id', $right->getItemId());
        $stmt->bindValue(':user_id', $right->getUserId());
        $stmt->bindValue(':type', $right->getType()->getValue());
        return $stmt->execute();
    }

    public function update(Right $right): bool
    {
        //@todo сделать обновление конкретных полей, чтобы не переписывалась вся сущность
        $stmt = $this->connection->prepare('
            UPDATE rights SET        
                item_id=:item_id,
                user_id=:user_id,
                type=:type
            WHERE id=:id
        ');

        $stmt->bindValue(':item_id', $right->getItemId());
        $stmt->bindValue(':user_id', $right->getUserId());
        $stmt->bindValue(':type', $right->getType()->getValue());
        $stmt->bindValue(':id', $right->getId()->toString());

        return $stmt->execute();
    }

    /**
     * @param ItemIdValue $itemId
     * @return Right[]
     * @throws DomainWrongEntityParamException
     */
    public function findAllByItemId(ItemIdValue $itemId): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM rights WHERE item_id=:item_id');
        $stmt->bindValue(':item_id', $itemId->getValue());
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
}
