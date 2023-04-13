<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Response;

use App\Application\DTO\Response\Item\ListItemIdsResponseDTO;
use App\Application\Mappers\MapperInterface;
use App\Domain\ValueObject\Item\ItemIdValue;

class ListItemIdsResponseMapper implements MapperInterface
{
    /**
     * @var ItemIdValue[]
     */
    private array $ids = [];

    /**
     * @param array $ids
     */
    public function setIds(array $ids): void
    {
        $this->ids = $ids;
    }


    public function map(): array
    {
        return array_map(function ($id) {
            return new ListItemIdsResponseDTO($id->getValue());
        }, $this->ids);
    }
}
