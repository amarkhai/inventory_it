<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Item\CreateItemRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\CreateItemUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateItemAction extends Action
{
    private CreateItemUseCase $useCase;

    public function __construct(
        LoggerInterface $logger,
        RequestValidator $validator,
        CreateItemUseCase $useCase
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
        $dto = new CreateItemRequestDTO($this->request, $this->requestValidator);

        $item = ($this->useCase)($dto);

        $this->logger->info("Item of id `{$item->getId()}` was created.");

        return $this->respondWithData($item);
    }
}
