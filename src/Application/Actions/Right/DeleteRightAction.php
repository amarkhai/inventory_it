<?php

declare(strict_types=1);

namespace App\Application\Actions\Right;

use App\Application\Actions\Action;
use App\Application\DTO\Request\Right\DeleteRightRequestDTO;
use App\Application\DTO\Request\Right\UpdateRightRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Right\DeleteRightUseCase;
use App\Application\UseCase\Right\UpdateRightUseCase;
use App\Domain\DomainException\DomainWrongEntityParamException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteRightAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected DeleteRightUseCase $useCase
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
        $dto = new DeleteRightRequestDTO($this->request, $this->requestValidator);

        $result = ($this->useCase)($dto);

        $this->logger->info("Right of id `{$dto->getId()}` was deleted.");

        return $this->respondWithData($result);
    }
}
