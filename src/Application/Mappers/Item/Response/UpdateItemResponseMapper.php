<?php

declare(strict_types=1);

namespace App\Application\Mappers\Item\Response;

use App\Application\DTO\Response\Item\UpdateItemResponseDTO;
use App\Application\Mappers\MapperInterface;

class UpdateItemResponseMapper implements MapperInterface
{
    private bool $result;

    /**
     * @param bool $result
     */
    public function setResult(bool $result): void
    {
        $this->result = $result;
    }

    /**
     * @return bool
     */
    public function getResult(): bool
    {
        return $this->result;
    }


    public function map(): UpdateItemResponseDTO
    {
        return new UpdateItemResponseDTO($this->getResult());
    }
}
