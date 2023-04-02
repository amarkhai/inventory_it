<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\DTO\RequestValidator;
use App\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    public function __construct(
        protected LoggerInterface $logger,
        protected RequestValidator $requestValidator,
        protected UserRepositoryInterface $userRepository
    ) {
        parent::__construct($logger, $requestValidator);
    }
}
