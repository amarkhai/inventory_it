<?php

declare(strict_types=1);

namespace App\Application\DTO\Request;

use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Запрос, доступный только аутентифицированным пользователям
 */
abstract class AuthenticatedRequestDTO extends RequestDTO
{
    #[Assert\Uuid]
    #[Assert\NotBlank]
    private UuidInterface $requester_id;

    /**
     * @return UuidInterface
     */
    public function getRequesterId(): UuidInterface
    {
        return $this->requester_id;
    }

    public function setValues(): void
    {
        $this->requester_id = $this->request->getAttribute('userUuid');
    }
}
