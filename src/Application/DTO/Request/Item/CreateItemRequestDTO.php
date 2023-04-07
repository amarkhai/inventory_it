<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use App\Domain\Constant\Constant;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateItemRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $temporary_id;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $owner_id;

    #[Assert\NotBlank]
    #[Assert\Type("string")]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $name;

    #[Assert\Type("string")]
    private ?string $description;

    #[Assert\Type("string")]
    #[Assert\Regex(Constant::ITEM_PATH_REGEX)]
    private ?string $parent_path;

    public function setValues(): void
    {
        parent::setValues();
        $this->temporary_id = $this->getBodyParam('temporary_id');
        $this->name = $this->getBodyParam('name');
        $this->description = $this->getBodyParam('description');
        $this->parent_path = $this->getBodyParam('parent_path');
        $this->owner_id = (string) $this->request->getAttribute('userIdentity')->getId();
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getTemporaryId(): ?string
    {
        return $this->temporary_id;
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getOwnerId(): ?string
    {
        return $this->owner_id;
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    /** @psalm-ignore-nullable-return */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * @return string|null
     */
    public function getParentPath(): ?string
    {
        return $this->parent_path;
    }
}
