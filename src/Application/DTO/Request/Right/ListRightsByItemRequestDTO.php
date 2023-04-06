<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Right;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class ListRightsByItemRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Type("int")]
    #[Assert\GreaterThan(0)]
    private ?int $item_id = null;

    public function setValues(): void
    {
        parent::setValues();
        $item_id = $this->getBodyParam('item_id');
        $this->item_id = (int) $item_id;
    }

    /**
     * @return int|null
     */
    /** @psalm-ignore-nullable-return */
    public function getItemId(): ?int
    {
        return $this->item_id;
    }
}
