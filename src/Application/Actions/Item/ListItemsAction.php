<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\ListItemsUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListItemsAction extends ItemAction
{
    private ListItemsUseCase $useCase;

    public function __construct(
        LoggerInterface $logger,
        RequestValidator $validator,
        ListItemsUseCase $useCase
    ) {
        parent::__construct($logger, $validator);
        $this->useCase = $useCase;
    }
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $dto = new ListItemsRequestDTO($this->request);
        $violations = $this->validator->validate($dto);

        if (!empty($violations)) {
            return $this->responseWithViolations($violations);
        }

        $data = ($this->useCase)($dto);

        $this->logger->info("Items list was viewed.");
        return $this->respondWithData($data);
    }
}
