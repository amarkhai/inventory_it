<?php

namespace App\Application\UseCase\Auth;

use App\Application\DTO\Request\Auth\AccessTokenByUsernameRequestDTO;
use App\Application\DTO\Response\Auth\AccessTokenResponseDTO;
use App\Application\Mappers\Auth\Response\AccessTokenResponseMapper;
use App\Application\UseCase\ActionUseCaseInterface;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\User\UserNotFoundException;
use App\Domain\Interactor\UserInteractor;
use App\Domain\ValueObject\User\UserNameValue;

class AccessTokenByUsernameUseCase implements ActionUseCaseInterface
{
    public function __construct(
        private readonly AccessTokenResponseMapper $responseMapper,
        private readonly UserInteractor $interactor,
        private readonly JWTTokenCreator $tokenCreator
    ) {
    }

    /**
     * @throws DomainWrongEntityParamException
     * @throws UserNotFoundException
     */
    public function __invoke(AccessTokenByUsernameRequestDTO $dto): AccessTokenResponseDTO
    {
        $user = $this->interactor->getOneByUsernameAndPassword(
            new UserNameValue($dto->getUsername()),
            $dto->getPassword()
        );

        $token = $this->tokenCreator->createForUser($user);

        $this->responseMapper->setToken($token);
        return $this->responseMapper->map();
    }
}
