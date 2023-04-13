<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class SearchItemsRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private ?string $term;

    public function setValues(): void
    {
        parent::setValues();
        $this->term = $this->getBodyParam('term');
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getTerm(): ?string
    {
        return $this->term;
    }
}
