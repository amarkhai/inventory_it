<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use Psr\Http\Message\ResponseInterface as Response;

class ListItemsAction extends ItemAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = $this->request->getAttribute('userUuid');

        $requestBody = $this->request->getParsedBody();

        $rootItemId = (isset($requestBody['rootItemId']))
            ? intval($requestBody['rootItemId']) : null;

        $items = $this->itemRepository->findAllForUser($userId, $rootItemId);

        $this->logger->info("Items list was viewed.");

        return $this->respondWithData($items);
    }
}
