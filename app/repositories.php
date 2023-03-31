<?php

declare(strict_types=1);

use App\Domain\Entity\Item\ItemRepository;
use App\Domain\Entity\User\UserRepository;
use App\Infrastructure\Persistence\Item\PDOItemRepository;
use App\Infrastructure\Persistence\User\PDOUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(PDOUserRepository::class),
        ItemRepository::class => \DI\autowire(PDOItemRepository::class),
    ]);
};
