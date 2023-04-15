<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\ListItemsUseCase;
use App\Domain\DomainException\DomainWrongEntityParamException;
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
     * @throws \JsonException
     * @throws DomainWrongEntityParamException
     */
    protected function action(): Response
    {
        $requestDto = new ListItemsRequestDTO($this->request, $this->requestValidator);
        $responseDto = ($this->useCase)($requestDto);

        $this->logger->info("Items list was viewed.");
        return $this->respondWithData($responseDto->getData())
            ->withHeader('X-Total-Count', (string) $responseDto->getTotalCount());
    }
}
