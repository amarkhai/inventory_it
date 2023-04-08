<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Item;

use App\Domain\DataMapper\Item\CreatedItemMapDataMapper;
use App\Domain\DataMapper\Item\ItemDataMapper;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use Ramsey\Uuid\UuidInterface;

class PDOItemRepository implements ItemRepositoryInterface
{
    public function __construct(
        private \PDO $connection
    ) {
    }

    /**
     * @return Item[]
     */
    public function findAll(): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM items');
        $stmt->execute();
        return array_map(fn ($row) => (new ItemDataMapper())->map($row), $stmt->fetchAll());
    }

    /**
     * @inheritDoc
     * @throws DomainWrongEntityParamException
     */
    public function findAllForUser(UuidInterface $userId, ?ItemIdValue $rootItemId = null): array
    {
        //@todo искать не только по owner_id, но и по тем, на которые есть права

        $query = 'SELECT * FROM items WHERE owner_id=:owner_id';
        $values = [':owner_id' => $userId];

        if ($rootItemId) {
            $query .= ' AND path <@ :root_item_id';
            $values[':root_item_id'] = $rootItemId->getValue();
        }

        $stmt = $this->connection->prepare($query);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return array_map(fn ($row) => (new ItemDataMapper())->map($row), $stmt->fetchAll());
    }

    /**
     * @inheritDoc
     */
    public function findOneForUserById(UuidInterface $userId, ItemIdValue $itemId): ?Item
    {
        $stmt = $this->connection
            ->prepare('SELECT * FROM items WHERE owner_id=:owner_id AND id=:id');
        $stmt->bindValue(':owner_id', $userId);
        $stmt->bindValue(':id', $itemId->getValue());
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ? (new ItemDataMapper())->map($row) : null;
    }

    /**
     * @inheritDoc
     */
    public function findAllForUserByTerm(UuidInterface $userId, string $term): array
    {
//@todo проверить
        $stmt = $this->connection->prepare('SELECT * FROM items WHERE owner_id=:owner_id');
        $stmt->bindValue(':owner_id', $userId);
        $stmt->execute();
        return array_map(fn ($row) => (new ItemDataMapper())->map($row), $stmt->fetchAll());
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
                path=(:path)::ltree
            WHERE id=:id
        ');
        $stmt->bindValue(':id', $item->getId()->getValue());
        $stmt->bindValue(':name', $item->getName()->getValue());
        $stmt->bindValue(':description', $item->getDescription()?->getValue());
        $stmt->bindValue(':path', $item->getPath()->getValue());
        $stmt->bindValue(':status', $item->getStatus()->getValue());
        return $stmt->execute();
    }

    public function findOneById(ItemIdValue $id): ?Item
    {
        $stmt = $this->connection->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute([$id->getValue()]);
        $item = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $item ? (new ItemDataMapper())->map($item) : null;
    }
}
