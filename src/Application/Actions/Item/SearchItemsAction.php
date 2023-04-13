<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Item\SearchItemsRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\SearchItemsUseCase;
use App\Domain\DomainException\DomainWrongEntityParamException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class SearchItemsAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected SearchItemsUseCase $useCase
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
        $dto = new SearchItemsRequestDTO($this->request, $this->requestValidator);

        $data = ($this->useCase)($dto);

        $this->logger->info("Items search list by `{$dto->getTerm()}` was viewed.");

        return $this->respondWithData($data);
    }
}
