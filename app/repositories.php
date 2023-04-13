<?php

declare(strict_types=1);

use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\ItemSearchRepositoryInterface;
use App\Domain\Repository\RightRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\Item\ESItemRepository;
use App\Infrastructure\Persistence\Item\PDOItemRepository;
use App\Infrastructure\Persistence\Right\PDORightRepository;
use App\Infrastructure\Persistence\User\PDOUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => \DI\autowire(PDOUserRepository::class),
        ItemRepositoryInterface::class => \DI\autowire(PDOItemRepository::class),
        ItemSearchRepositoryInterface::class => \DI\autowire(ESItemRepository::class),
        RightRepositoryInterface::class => \DI\autowire(PDORightRepository::class),
    ]);
};
