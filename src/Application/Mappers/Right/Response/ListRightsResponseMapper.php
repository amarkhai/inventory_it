<?php

declare(strict_types=1);

namespace App\Application\Mappers\Right\Response;

use App\Application\DTO\Response\Right\ListRightResponseDTO;
use App\Application\Mappers\MapperInterface;
use App\Domain\Entity\Right\Right;

class ListRightsResponseMapper implements MapperInterface
{
    /**
     * @var Right[]
     */
    private array $rights = [];

    /**
     * @param Right[] $rights
     */
    public function setRights(array $rights): void
    {
        $this->rights = $rights;
    }

    public function map(): array
    {
        return array_map(function ($right) {
            return new ListRightResponseDTO(
                $right->getId()->toString(),
                $right->getPath()->getValue(),
                $right->getUserId()->toString(),
                $right->getType()->getValue(),
            );
        }, $this->rights);
    }
}
