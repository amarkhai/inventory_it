<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Item;

use App\Domain\DataMapper\Item\CreatedItemMapDataMapper;
use App\Domain\DataMapper\Item\ItemDataMapper;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class PDOItemRepository implements ItemRepositoryInterface
{
    public function __construct(
        private readonly \PDO $connection
    ) {
    }

    /**
     * @inheritDoc
     * @throws DomainWrongEntityParamException
     */
    public function findAllForUser(
        UuidInterface $userId,
        ?ItemPathValue $rootItemPath = null,
        int $offset = 0,
        int $limit = 20
    ): array {
        $query = 'SELECT * FROM items WHERE 
                    (
                        owner_id=:user_id 
                        OR path <@ ARRAY(select path from rights where user_id = :user_id)
                    )';
        $values = [':user_id' => $userId];

        if ($rootItemPath) {
            $query .= ' AND path <@ :root_item_path';
            $values[':root_item_path'] = $rootItemPath->getValue();
        }

        $query .= " ORDER BY name LIMIT $limit OFFSET $offset";

        $stmt = $this->connection->prepare($query);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return array_map(fn ($row) => (new ItemDataMapper())->map($row), $stmt->fetchAll());
    }

    public function countAllForUser(
        UuidInterface $userId,
        ?ItemPathValue $rootItemPath = null
    ): int {
        $query = 'SELECT count(*) as totalCount FROM items WHERE 
                    (
                        owner_id=:user_id 
                        OR path <@ ARRAY(select path from rights where user_id = :user_id)
                    )';
        $values = [':user_id' => $userId];

        if ($rootItemPath) {
            $query .= ' AND path <@ :root_item_path';
            $values[':root_item_path'] = $rootItemPath->getValue();
        }

        $stmt = $this->connection->prepare($query);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * @inheritDoc
     */
    public function findOneForUserById(UuidInterface $userId, ItemIdValue $itemId): ?Item
    {
        $stmt = $this->connection
            ->prepare('SELECT * FROM items WHERE 
                        id=:id
                        AND (
                            owner_id=:user_id
                            OR path <@ ARRAY(select path from rights where user_id = :user_id)
                        )                       
            ');
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':id', $itemId->getValue());
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ? (new ItemDataMapper())->map($row) : null;
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function insert(
        Item $item,
        UuidInterface $temporaryId,
        ?ItemPathValue $parentPath
    ): JustCreatedItemMap {
        $pathPrefix = $parentPath ? $parentPath->getValue() . '.' : '';

        $stmt = $this->connection->prepare('
            INSERT INTO public.items (name, description, owner_id, path, status)
            VALUES (:name, :description, :owner_id, (:path_prefix || lastval())::ltree, :status)
            RETURNING id, path
        ');
        $stmt->bindValue(':name', $item->getName());
        $stmt->bindValue(':description', $item->getDescription());
        $stmt->bindValue(':owner_id', $item->getOwnerId());
        $stmt->bindValue(':path_prefix', $pathPrefix);
        $stmt->bindValue(':status', $item->getStatus()->getValue());
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row === false) {
            throw new \RuntimeException('Не удалось создать объект ItemIdMapping для Item с id=' . $item->getId());
        }

        $row['temporary_id'] = $temporaryId->toString();
        return (new CreatedItemMapDataMapper())->map($row);
    }

    public function update(Item $item): bool
    {
        //@todo сделать обновление конкретных полей, чтобы не переписывалась вся сущность
        $stmt = $this->connection->prepare('
            UPDATE public.items SET
                name=:name,
                description=:description,
                status=:status,
                path=(:path)::ltree,
                updated_at=:updated_at
            WHERE id=:id
        ');
        $stmt->bindValue(':id', $item->getId()->getValue());
        $stmt->bindValue(':name', $item->getName()->getValue());
        $stmt->bindValue(':description', $item->getDescription()?->getValue());
        $stmt->bindValue(':path', $item->getPath()->getValue());
        $stmt->bindValue(':status', $item->getStatus()->getValue());
        $stmt->bindValue(':updated_at', (new DateTimeImmutable())->format('Y-m-d H:i:s'));
        return $stmt->execute();
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function findOneById(ItemIdValue $id): ?Item
    {
        $stmt = $this->connection->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute([$id->getValue()]);
        $item = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $item ? (new ItemDataMapper())->map($item) : null;
    }

    /**
     * @throws DomainWrongEntityParamException
     */
    public function findOneByPath(ItemPathValue $path): ?Item
    {
        $stmt = $this->connection->prepare('SELECT * FROM items WHERE path = ?');
        $stmt->execute([$path->getValue()]);
        $item = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $item ? (new ItemDataMapper())->map($item) : null;
    }
}
