<?php

declare(strict_types=1);

namespace App\Application\DTO\Response\Auth;

use App\Application\DTO\Response\ResponseDTO;

class AccessTokenResponseDTO extends ResponseDTO
{
    public function __construct(
        private readonly string $iat,
        private readonly string $exp,
        private readonly string $nbf,
        private readonly string $access_token,
    ) {
    }

    /**
     * @return string
     */
    public function getIat(): string
    {
        return $this->iat;
    }

    /**
     * @return string
     */
    public function getExp(): string
    {
        return $this->exp;
    }

    /**
     * @return string
     */
    public function getNbf(): string
    {
        return $this->nbf;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    public function jsonSerialize(): array
    {
        return [
            'iat' => $this->getIat(),
            'exp' => $this->getExp(),
            'nbf' => $this->getNbf(),
            'access_token' => $this->getAccessToken(),
        ];
    }
}
