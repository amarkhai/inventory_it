<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use App\Domain\Constant\Constant;
use Symfony\Component\Validator\Constraints as Assert;

class ListItemsRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\Type("string")]
    #[Assert\Regex(Constant::ITEM_PATH_REGEX)]
    private ?string $root_item_path;

    public function setValues(): void
    {
        parent::setValues();
        $this->root_item_path = $this->getBodyParam('root_item_path');
    }

    /**
     * @return string|null
     */
    public function getRootItemPath(): ?string
    {
        return $this->root_item_path;
    }
}
