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
    private UuidInterface $userId;


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->userId = $this->request->getAttribute('userUuid');
    }


    /**
     * @return UuidInterface
     */
    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
