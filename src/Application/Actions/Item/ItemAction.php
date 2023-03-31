<?php

declare(strict_types=1);

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Application\DTO\RequestValidator;
use Psr\Log\LoggerInterface;

abstract class ItemAction extends Action
{
    protected RequestValidator $validator;

    public function __construct(
        LoggerInterface $logger,
        RequestValidator $validator
    ) {
        parent::__construct($logger);
        $this->validator = $validator;
    }
}
