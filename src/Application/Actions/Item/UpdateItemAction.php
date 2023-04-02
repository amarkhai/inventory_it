<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Item\UpdateItemRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\UpdateItemUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateItemAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected UpdateItemUseCase $useCase
    ) {
        parent::__construct($logger, $requestValidator);
    }


    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $dto = new UpdateItemRequestDTO($this->request);

        $violations = $this->validateRequestDTO($dto);
        if (!empty($violations)) {
            return $this->respondWithViolations($violations);
        }

        $result = ($this->useCase)($dto);

        $this->logger->info("Item of id `{$dto->getId()}` was updated.");

        return $this->respondWithData($result);
    }
}
