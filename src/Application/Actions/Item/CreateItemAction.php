<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\DTO\Request\Item\CreateItemRequestDTO;
use App\Application\DTO\RequestValidator;
use App\Application\UseCase\Item\CreateItemUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateItemAction extends ItemAction
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
     */
    protected function action(): Response
    {
        $dto = new CreateItemRequestDTO($this->request);
        $violations = $this->validator->validate($dto);

        if (!empty($violations)) {
            return $this->responseWithViolations($violations);
        }

        $item = ($this->useCase)($dto);

//        $item = $this->itemRepository->findOneForUserById(
//            $dto->getUserId(),
//            $dto->getItemId()
//        );

        $this->logger->info("Item of id `{$item->getId()}` was created.");

        return $this->respondWithData($item);
    }
}
