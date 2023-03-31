<?php

declare(strict_types=1);

namespace App\Domain\Entity\Item;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ItemNotFoundException extends DomainRecordNotFoundException
{
    /**
     * @var string
     */
    public $message = 'The item you requested does not exist or you have no rights to read it.';
}
