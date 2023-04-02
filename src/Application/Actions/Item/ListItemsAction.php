<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\ListItemsUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListItemsAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected ListItemsUseCase $useCase
    ) {
        parent::__construct($logger, $requestValidator);
    }
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $dto = new ListItemsRequestDTO($this->request);

        $violations = $this->validateRequestDTO($dto);
        if (!empty($violations)) {
            return $this->respondWithViolations($violations);
        }

        $data = ($this->useCase)($dto);

        $this->logger->info("Items list was viewed.");
        return $this->respondWithData($data);
    }
}
