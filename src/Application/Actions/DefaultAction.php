<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;

class DefaultAction extends Action
{
    protected function action(): Response
    {
        $this->logger->info("Run default action.");

        return $this->respondWithData([
            'msg' => 'Hello World!'
        ]);
    }
}