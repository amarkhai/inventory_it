<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validator;
use Lcobucci\JWT\Parser;
use Psr\Clock\ClockInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class CheckJWTTokenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly string $secret,
        private readonly Parser $parser,
        private readonly Signer $signer,
        private readonly Validator $validator,
        private readonly ClockInterface $clock,
        private readonly UserRepository $userRepository
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeaders = $request->getHeader('Authorization');
        if (\count($authHeaders) !== 1) {
            throw new \RuntimeException('Incorrect header');
        }

        \preg_match('/^Bearer (\S+)$/', $authHeaders[0], $matches);

        if (!isset($matches[1])) {
            throw new \RuntimeException('Incorrect header');
        }
        $token = $matches[1];

        $parsedToken = $this->parser->parse($token);

        $signingKey = InMemory::plainText($this->secret);
        $this->validator->assert(
            $parsedToken,
            new SignedWith($this->signer, $signingKey),
            new StrictValidAt($this->clock)
        );

        $userUuid = $parsedToken->claims()->get('uuid');

        $user = $this->userRepository->findUserOfId(Uuid::fromString($userUuid));

        return $handler->handle($request->withAttribute('userUuid', $user->getId()));
    }
}