<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth\Token;

use App\Application\Actions\Action;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Auth\JWTTokenCreator;
use App\Domain\Repository\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class AccessTokenByPasswordAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        private readonly UserRepositoryInterface $userRepository,
        private readonly JWTTokenCreator $JWTTokenCreator
    ) {
        parent::__construct($logger, $requestValidator);
    }
    protected function action(): Response
    {
        $id = $this->request->getParsedBody()['id'] ?? null;
        $password = $this->request->getParsedBody()['password'] ?? null;

        if (\is_null($id) || \is_null($password)) {
            return $this->respondWithData('Incorrect request', 400);
        }

        $user = $this->userRepository->findUserOfId(Uuid::fromString($id));

        if (!\password_verify($password, $user->getPassword())) {
            return $this->respondWithData('Incorrect credentials', 400);
        }

        $token = $this->JWTTokenCreator->createForUser($user);

        return $this->respondWithData([
            'iat' => $token->claims()->get('iat')->format('c'),
            'exp' => $token->claims()->get('exp')->format('c'),
            'nbf' => $token->claims()->get('nbf')->format('c'),
            'access_token' => $token->toString(),
        ]);
    }
}
