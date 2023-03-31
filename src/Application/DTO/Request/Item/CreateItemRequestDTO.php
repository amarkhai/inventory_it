<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateItemRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private string $temporary_id;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private string $owner_id;

    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private string $name;

    #[Assert\Type("string")]
    private ?string $description;

    #[Assert\Type("string")]
    private ?string $parent_path;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->temporary_id = $this->getBodyParam('temporary_id') ?? '';
        $this->name = $this->getBodyParam('name') ?? '';
        $this->description = $this->getBodyParam('description');
        $this->parent_path = $this->getBodyParam('parent_path');
        $this->owner_id = (string) $this->request->getAttribute('userUuid');
    }

    /**
     * @return string
     */
    public function getTemporaryId(): string
    {
        return $this->temporary_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->owner_id;
    }
}
