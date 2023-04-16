<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Item;

use App\Domain\DataMapper\Item\PartialItemDataMapper;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Repository\ItemSearchRepositoryInterface;
use App\Domain\ValueObject\Item\ItemSearchTermValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Infrastructure\Persistence\ESRepository;
use Ramsey\Uuid\UuidInterface;

class ESItemRepository extends ESRepository implements ItemSearchRepositoryInterface
{
    protected const INDEX_NAME = 'items';

    /**
     * @inheritDoc
     * @throws DomainWrongEntityParamException
     */
    public function searchAllForUser(UuidInterface $userId, ItemSearchTermValue $termValue): array
    {
        $query = [
            'bool' => [
                'filter' => [
                    [
                        'term' => [
                            'visible_for_users' => $userId->toString(),
                        ],
                    ],
                ],
                'should' => [
                    [
                        'match' => [
                            'name' => ['query' => $termValue->getValue()],
                        ],
                    ],
                    [
                        'match' => [
                            'description' => ['query' => $termValue->getValue()]
                        ],
                    ],
                ],
                "minimum_should_match" => 1
            ]
        ];

        return array_map(
            fn ($row) => (new PartialItemDataMapper())->map($row['_source']),
            $this->getFoundedItems($this->search($query))
        );
    }

    protected function getIndexName(): string
    {
        return self::INDEX_NAME;
    }
}
