<?php

declare(strict_types=1);

namespace App\Application\Actions\Right;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Right\CreateRightRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Right\CreateRightUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateRightAction extends Action
{
    private CreateRightUseCase $useCase;

    public function __construct(
        LoggerInterface $logger,
        RequestValidator $validator,
        CreateRightUseCase $useCase
    ) {
        parent::__construct($logger, $validator);
        $this->useCase = $useCase;
    }

    /**
     * {@inheritdoc}
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $dto = new CreateRightRequestDTO($this->request, $this->requestValidator);

        $result = ($this->useCase)($dto);

        $this->logger->info("Right of id `{$dto->getId()}` was created.");

        return $this->respondWithData($result);
    }
}
