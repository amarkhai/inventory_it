<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateItemRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Type("int")]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private ?string $name;

    #[Assert\Type("string")]
    private ?string $description;

    #[Assert\NotBlank]
    #[Assert\Type("string")]
    #[Assert\Choice(['active', 'deleted'])]
    private ?string $status;

    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private ?string $path;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $owner_id;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->id = (int) $this->getRouteParam('id');
        $this->name = $this->getBodyParam('name');
        $this->description = $this->getBodyParam('description');
        $this->status = $this->getBodyParam('status');
        $this->path = $this->getBodyParam('path');
        $this->owner_id = (string) $this->request->getAttribute('userUuid');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
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
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getOwnerId(): ?string
    {
        return $this->owner_id;
    }
}
