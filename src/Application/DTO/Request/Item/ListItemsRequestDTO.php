<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class ListItemsRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\Type("int")]
    #[Assert\GreaterThan(0)]
    private ?int $root_item_id = null;

    public function setValues(): void
    {
        parent::setValues();
        $root_item_id = $this->getBodyParam('root_item_id');
        if (is_numeric($root_item_id)) {
            $this->root_item_id = (int) $root_item_id;
        }
    }

    /**
     * @return int|null
     */
    public function getRootItemId(): ?int
    {
        return $this->root_item_id;
    }
}
