<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Auth;

use App\Application\DTO\Request\RequestDTO;
use App\Domain\Constant\Constant;
use Symfony\Component\Validator\Constraints as Assert;

class AccessTokenByUsernameRequestDTO extends RequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: Constant::USER_NAME_MIN_LENGTH, max: Constant::USER_NAME_MAX_LENGTH)]
    private ?string $username;

    #[Assert\NotBlank]
    #[Assert\Length(min: Constant::PASSWORD_MIN_LENGTH, max: Constant::PASSWORD_MAX_LENGTH)]
    private ?string $password;

    public function setValues(): void
    {
        $this->username = $this->getBodyParam('username');
        $this->password = $this->getBodyParam('password');
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    /** @psalm-ignore-nullable-return */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
