<?php

declare(strict_types=1);

namespace App\Application\DTO\Response;

class DataWithTotalCountResponseDTO extends ResponseDTO
{
    /**
     * @param array $data
     * @param int $totalCount
     */
    public function __construct(
        private readonly array $data,
        private readonly int $totalCount,
    ) {
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'data' => $this->getData(),
            'totalCount' => $this->getTotalCount(),
        ];
    }
}
