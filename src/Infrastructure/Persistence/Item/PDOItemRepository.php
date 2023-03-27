<?php

namespace App\Infrastructure\Persistence\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemIdMapping;
use App\Domain\Item\ItemRepository;
use App\Domain\Item\ItemStatus;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;

readonly class PDOItemRepository implements ItemRepository
{
    public function __construct(private \PDO $connection)
    {
    }

    /**
     * @return Item[]
     */
    public function findAll(): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM items');
        $stmt->execute();
        return array_map(self::class . '::rowToObject', $stmt->fetchAll());
    }

        /**
     * @inheritDoc
     */
    public function findAllForUser(UuidInterface $userId, ?int $rootItemId): array
    { //@todo проверить
        return ($rootItemId) ? $this->findSubtree($userId, $rootItemId) : $this->findAllAvailable($userId);
    }

    /**
     * @inheritDoc
     */
    public function findOneForUserById(UuidInterface $userId, int $itemId): Item
    {//@todo проверить
        $stmt = $this->connection
            ->prepare('SELECT * FROM items WHERE owner_id=:owner_id AND id=:id');
        $stmt->bindValue(':owner_id', $userId);
        $stmt->bindValue(':id', $itemId);
        $stmt->execute();
        return $stmt->fetchObject(Item::class);
    }

    /**
     * @inheritDoc
     */
    public function findAllForUserByTerm(UuidInterface $userId, string $term): array
    {//@todo проверить
        $stmt = $this->connection->prepare('SELECT * FROM items WHERE owner_id=:owner_id');
        $stmt->bindValue(':owner_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Item::class);
    }

    /**
     * @inheritDoc
     */
    public function insert(Item $item): ItemIdMapping
    {
        $parentPath = $item->getParentPath();
        $parentPath = $parentPath ? $parentPath . '.' : '';

        $stmt = $this->connection->prepare('
            INSERT INTO public.items (name, description, owner_id, path, status)
            VALUES (:name, :description, :owner_id, (:parent_path || lastval())::ltree, :status)
            RETURNING id, path
        ');
        $stmt->bindValue(':name', $item->getName());
        $stmt->bindValue(':description', $item->getDescription());
        $stmt->bindValue(':owner_id', $item->getOwnerId());
        $stmt->bindValue(':parent_path', $parentPath);
        $stmt->bindValue(':status', $item->getStatus()->status());
        $stmt->execute();

        return $stmt->fetchObject(ItemIdMapping::class, [$item->getT_id()]);
    }

    private function findSubtree(UuidInterface $userId, int $rootItemId): array
    {//@todo проверить
        $stmt = $this->connection
            ->prepare('SELECT * FROM items WHERE owner_id=:owner_id AND path ~ \'*.:root_id.*\'');
        $stmt->bindValue(':root_id', $rootItemId);
        $stmt->bindValue(':owner_id', $userId);
        $stmt->execute();
        return array_map(self::class . '::rowToObject', $stmt->fetchAll());
    }

    private function findAllAvailable(UuidInterface $userId): array
    {//@todo проверить
        $stmt = $this->connection->prepare('SELECT * FROM items WHERE owner_id=:owner_id');
        $stmt->bindValue(':owner_id', $userId);
        $stmt->execute();
        return array_map(self::class . '::rowToObject', $stmt->fetchAll());
    }

    public function update(Item $item): bool
    {//@todo проверить
        $stmt = $this->connection->prepare('
            UPDATE public.items SET 
                name=:name, 
                description=:description, 
                path=:path, 
                status=:status
            WHERE id=:id
        ');
        $stmt->bindValue(':id', $item->getId());
        $stmt->bindValue(':name', $item->getName());
        $stmt->bindValue(':description', $item->getDescription());
        $stmt->bindValue(':path', $item->getPath());
        $stmt->bindValue(':status', $item->getStatus());
        $stmt->execute();
        return $stmt->fetchObject(ItemIdMapping::class, ['t_id' => $item->getT_id()]);
    }

    private static function rowToObject(array $row): Item
    {
        return new Item(
            $row['id'],
            null,
            $row['path'],
            null,
            ItemStatus::from($row['status']),
            UuidV4::fromString($row['owner_id']),
            $row['name'],
            $row['description'],
        );
    }
}