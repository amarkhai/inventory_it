<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\DTO\Request\Item\ViewItemRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\ViewItemUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ViewItemAction extends ItemAction
{
    private ViewItemUseCase $useCase;

    public function __construct(
        LoggerInterface $logger,
        RequestValidator $validator,
        ViewItemUseCase $useCase
    ) {
        parent::__construct($logger, $validator);
        $this->useCase = $useCase;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $dto = new ViewItemRequestDTO($this->request);
        $violations = $this->validator->validate($dto);

        if (!empty($violations)) {
            return $this->responseWithViolations($violations);
        }

        $data = ($this->useCase)($dto);

        $this->logger->info("Item of id `{$dto->getItemId()}` was viewed.");

        return $this->respondWithData($data);
    }
}
