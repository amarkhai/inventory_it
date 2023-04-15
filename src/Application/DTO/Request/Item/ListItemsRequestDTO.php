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

    #[Assert\NotBlank]
    #[Assert\Type("int")]
    #[Assert\GreaterThan(0)]
    private int $page;

    public function setValues(): void
    {
        parent::setValues();
        $this->root_item_path = $this->getBodyParam('root_item_path');
        $page = $this->getBodyParam('page');
        $this->page = is_numeric($page) ? (int) $page : 1;
    }

    /**
     * @return string|null
     */
    public function getRootItemPath(): ?string
    {
        return $this->root_item_path;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
