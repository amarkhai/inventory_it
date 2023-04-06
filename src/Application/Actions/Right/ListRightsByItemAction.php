<?php

declare(strict_types=1);

namespace App\Application\Actions\Right;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Item\ListItemsRequestDTO;
use App\Application\DTO\Request\Right\ListRightsByItemRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\ListItemsUseCase;
use App\Application\UseCase\Right\ListRightsByItemUseCase;
use App\Domain\DomainException\DomainWrongEntityParamException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListRightsByItemAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected ListRightsByItemUseCase $useCase
    ) {
        parent::__construct($logger, $requestValidator);
    }

    /**
     * {@inheritdoc}
     * @throws DomainWrongEntityParamException
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $dto = new ListRightsByItemRequestDTO($this->request, $this->requestValidator);

        $data = ($this->useCase)($dto);

        $this->logger->info("Rights list for item `{$dto->getItemId()}` was viewed.");
        return $this->respondWithData($data);
    }
}
