<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class ViewItemRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Type("int")]
    #[Assert\GreaterThan(0)]
    private int $itemId;

    public function setValues(): void
    {
        parent::setValues();
        $this->itemId = (int) $this->getRouteParam('id');
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }
}
