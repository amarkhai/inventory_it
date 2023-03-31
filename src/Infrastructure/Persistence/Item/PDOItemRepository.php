<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Item;

use App\Domain\DataMapper\Item\CreatedItemMapDataMapper;
use App\Domain\DataMapper\Item\ItemDataMapper;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\JustCreatedItemMap;
use App\Domain\Entity\Item\ItemNotFoundException;
use App\Domain\Entity\Item\ItemRepository;
use Ramsey\Uuid\UuidInterface;

class PDOItemRepository implements ItemRepository
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
     */
    public function findAllForUser(UuidInterface $userId, ?int $rootItemId = null): array
    {
        return ($rootItemId) ? $this->findSubtree($userId, $rootItemId) : $this->findAllAvailable($userId);
    }

    /**
     * @inheritDoc
     */
    public function findOneForUserById(UuidInterface $userId, int $itemId): Item
    {
        $stmt = $this->connection
            ->prepare('SELECT * FROM items WHERE owner_id=:owner_id AND id=:id');
        $stmt->bindValue(':owner_id', $userId);
        $stmt->bindValue(':id', $itemId);
        $stmt->execute();
        $row = $stmt->fetch();
        if (empty($row)) {
            throw new ItemNotFoundException();
        }
        return (new ItemDataMapper())->map($row);
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
     * @inheritDoc
     */
    public function insert(
        Item $item,
        string $temporaryId,
        ?string $parentPath
    ): JustCreatedItemMap {
        $pathPrefix = $parentPath ? $parentPath . '.' : '';

        $stmt = $this->connection->prepare('
            INSERT INTO public.items (name, description, owner_id, path, status)
            VALUES (:name, :description, :owner_id, (:path_prefix || lastval())::ltree, :status)
            RETURNING id, path
        ');
        $stmt->bindValue(':name', $item->getName());
        $stmt->bindValue(':description', $item->getDescription());
        $stmt->bindValue(':owner_id', $item->getOwnerId());
        $stmt->bindValue(':path_prefix', $pathPrefix);
        $stmt->bindValue(':status', $item->getStatus());
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row === false) {
            throw new \RuntimeException('Не удалось создать объект ItemIdMapping для Item с id=' . $item->getId());
        }

        $row['temporary_id'] = $temporaryId;
        return (new CreatedItemMapDataMapper())->map($row);
    }

    private function findSubtree(UuidInterface $userId, int $rootItemId): array
    {
        $stmt = $this->connection
            ->prepare('SELECT * FROM items WHERE owner_id=:owner_id AND path <@ :root_item_id');
        $stmt->bindValue(':root_item_id', $rootItemId);
        $stmt->bindValue(':owner_id', $userId);
        $stmt->execute();
        return array_map(fn ($row) => (new ItemDataMapper())->map($row), $stmt->fetchAll());
    }

    private function findAllAvailable(UuidInterface $userId): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM items WHERE owner_id=:owner_id');
        $stmt->bindValue(':owner_id', $userId);
        $stmt->execute();
        return array_map(fn ($row) => (new ItemDataMapper())->map($row), $stmt->fetchAll());
    }

    public function update(Item $item): bool
    {
        //@todo implement
        return false;
//        $stmt = $this->connection->prepare('
//            UPDATE public.items SET
//                name=:name,
//                description=:description,
//                path=:path,
//                status=:status
//            WHERE id=:id
//        ');
//        $stmt->bindValue(':id', $item->getId());
//        $stmt->bindValue(':name', $item->getName());
//        $stmt->bindValue(':description', $item->getDescription());
//        $stmt->bindValue(':path', $item->getPath());
//        $stmt->bindValue(':status', $item->getStatus());
//        $stmt->execute();
//        return $stmt->fetchObject(JustCreatedItemMap::class, ['t_id' => $item->getT_id()]);
    }
}
