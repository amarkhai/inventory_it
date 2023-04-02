<?php

declare(strict_types=1);

use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\Item\PDOItemRepository;
use App\Infrastructure\Persistence\User\PDOUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => \DI\autowire(PDOUserRepository::class),
        ItemRepositoryInterface::class => \DI\autowire(PDOItemRepository::class),
    ]);
};
