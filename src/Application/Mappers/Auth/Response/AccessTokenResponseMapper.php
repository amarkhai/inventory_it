<?php

declare(strict_types=1);

namespace App\Application\Mappers\Auth\Response;

use App\Application\DTO\Response\Auth\AccessTokenResponseDTO;
use App\Application\Mappers\MapperInterface;
use Lcobucci\JWT\UnencryptedToken;

class AccessTokenResponseMapper implements MapperInterface
{
    private UnencryptedToken $token;

    /**
     * @param UnencryptedToken $token
     */
    public function setToken(UnencryptedToken $token): void
    {
        $this->token = $token;
    }

    public function map(): AccessTokenResponseDTO
    {
        return new AccessTokenResponseDTO(
            $this->token->claims()->get('iat')->format('c'),
            $this->token->claims()->get('exp')->format('c'),
            $this->token->claims()->get('nbf')->format('c'),
            $this->token->toString(),
        );
    }
}
