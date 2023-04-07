<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth\Token;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Auth\AccessTokenByUsernameRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Auth\AccessTokenByUsernameUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AccessTokenByPasswordAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected AccessTokenByUsernameUseCase $useCase
    ) {
        parent::__construct($logger, $requestValidator);
    }

    /**
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $dto = new AccessTokenByUsernameRequestDTO($this->request, $this->requestValidator);

        $item = ($this->useCase)($dto);

        $this->logger->info("Access token for user `{$dto->getUsername()}` was created.");

        return $this->respondWithData($item);
    }
}
