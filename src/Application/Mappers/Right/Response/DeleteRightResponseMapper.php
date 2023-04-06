<?php

declare(strict_types=1);

namespace App\Application\Mappers\Right\Response;

use App\Application\DTO\Response\Right\CreateRightResponseDTO;
use App\Application\DTO\Response\Right\DeleteRightResponseDTO;
use App\Application\DTO\Response\Right\UpdateRightResponseDTO;
use App\Application\Mappers\MapperInterface;

class DeleteRightResponseMapper implements MapperInterface
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

    public function map(): DeleteRightResponseDTO
    {
        return new DeleteRightResponseDTO($this->getResult());
    }
}
