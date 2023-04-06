<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Right;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteRightRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $id;

    public function setValues(): void
    {
        parent::setValues();
        $this->id = $this->getRouteParam('id');
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getId(): ?string
    {
        return $this->id;
    }
}
