<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Right;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRightRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $id;

    #[Assert\NotBlank]
    #[Assert\Type("int")]
    #[Assert\GreaterThan(0)]
    private ?int $item_id;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $user_id;

    #[Assert\NotBlank]
    #[Assert\Type("string")]
    #[Assert\Choice(['ro', 'rw'])]
    private ?string $type;

    public function setValues(): void
    {
        parent::setValues();
        $this->id = $this->getBodyParam('id');
        $this->item_id = (int) $this->getBodyParam('item_id');
        $this->user_id = $this->getBodyParam('user_id');
        $this->type = $this->getBodyParam('type');
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    /** @psalm-ignore-nullable-return */
    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getType(): ?string
    {
        return $this->type;
    }
}
