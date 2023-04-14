<?php

declare(strict_types=1);

use App\Application\DTO\RequestValidator;
use App\Application\Middleware\CheckJWTTokenMiddleware;
use App\Application\Settings\SettingsInterface;
use App\Application\UseCase\Auth\JWTTokenCreator;
use App\Domain\Repository\UserRepositoryInterface;
use DI\ContainerBuilder;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\ClientInterface;
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
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        ValidatorInterface::class =>
            Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator(),
        RequestValidator::class => function (ContainerInterface $c) {
            $validator = $c->get(ValidatorInterface::class);
            return new RequestValidator($validator);
        },
        \PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            /** @var array{dsn: string, user: string, password: string} $dbSettings */
            $dbSettings = $settings->get('db');

            $pdo = new PDO($dbSettings['dsn'], $dbSettings['user'], $dbSettings['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        },
        Client::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            /** @var array{host: string, user: string, password: string} $esSettings */
            $esSettings = $settings->get('es');

            return ClientBuilder::create()
                ->setHosts([$esSettings['host']])
                ->setBasicAuthentication($esSettings['user'], $esSettings['password'])
                ->build();
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
                $c->get(UserRepositoryInterface::class),
            );
        },
    ]);
};
