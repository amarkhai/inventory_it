<?php

declare(strict_types=1);

use App\Application\Middleware\CheckJWTTokenMiddleware;
use App\Application\Settings\SettingsInterface;
use App\Application\UseCase\Auth\JWTTokenCreator;
use App\Domain\User\UserRepository;
use DI\ContainerBuilder;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Validator;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Clock\ClockInterface as Clock;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        \PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            /** @var array{dsn: string, user: string, password: string} $dbSettings */
            $dbSettings = $settings->get('db');

            return new PDO($dbSettings['dsn'], $dbSettings['user'], $dbSettings['password']);
        },
        JWTTokenCreator::class => function (ContainerInterface $c) {
            $settings = $settings = $c->get(SettingsInterface::class);
            /** @var array{token_expiration_time: int, secret: string} $jwtSetting */
            $jwtSetting = $settings->get('JWT');

            return new JWTTokenCreator((int) $jwtSetting['token_expiration_time'], $jwtSetting['secret']);
        },
        Clock::class => new class implements Clock {
            public function now(): DateTimeImmutable
            {
                return new DateTimeImmutable();
            }
        },
        CheckJWTTokenMiddleware::class => function (ContainerInterface $c) {
            $settings = $settings = $c->get(SettingsInterface::class);
            /** @var array{token_expiration_time: int, secret: string} $jwtSetting */
            $jwtSetting = $settings->get('JWT');

            return new CheckJWTTokenMiddleware(
                $jwtSetting['secret'],
                new Parser(new JoseEncoder()),
                new Sha256(),
                new Validator(),
                $c->get(Clock::class),
                $c->get(UserRepository::class),
            );
        },
    ]);
};
