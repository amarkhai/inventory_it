<?php

namespace App\Domain\Interactor;

use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\User\UserNameValue;

class UserInteractor
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @param UserNameValue $username
     * @param string $password
     * @return User
     * @throws UserNotFoundException
     */
    public function getOneByUsernameAndPassword(
        UserNameValue $username,
        string $password
    ): User {

        $user = $this->userRepository->findOneByUsername($username);

        if (!$user || !password_verify($password, $user->getPasswordHash()->getValue())) {
            throw new UserNotFoundException('User with this credentials not found.');
        }

        return $user;
    }
}
