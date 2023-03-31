<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Response;

use App\Application\DTO\Response\Item\CreateItemResponseDTO;
use App\Application\Mappers\MapperInterface;
use App\Domain\Entity\Item\JustCreatedItemMap;

class CreateItemResponseMapper implements MapperInterface
{
    /**
     * @var JustCreatedItemMap
     */
    private JustCreatedItemMap $itemMap;

    /**
     * @param JustCreatedItemMap $itemMap
     */
    public function setItemMap(JustCreatedItemMap $itemMap): void
    {
        $this->itemMap = $itemMap;
    }

    public function map(): CreateItemResponseDTO
    {
        return new CreateItemResponseDTO(
            $this->itemMap->getId()->getValue(),
            $this->itemMap->getTemporaryId()->getValue(),
            $this->itemMap->getPath()->getValue(),
        );
    }
}
