<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Uuid;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        //@todo снести или сделать как остальные actions
        $userId = $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId(Uuid::fromString($userId));

        $this->logger->info("User of id `{$userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
