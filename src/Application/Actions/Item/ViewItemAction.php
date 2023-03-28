<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use Psr\Http\Message\ResponseInterface as Response;

class ViewItemAction extends ItemAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $itemId = (int) $this->resolveArg('id');
        $userId = $this->request->getAttribute('userUuid');
        $item = $this->itemRepository->findOneForUserById($userId, $itemId);

        $this->logger->info("User of id `{$itemId}` was viewed.");

        return $this->respondWithData($item);
    }
}
