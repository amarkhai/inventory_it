<?php

declare(strict_types=1);

namespace App\Application\UseCase\Auth;

use App\Domain\User\User;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\UnencryptedToken;

class JWTTokenCreator
{
    /**
     * @param non-empty-string $secret
     */
    public function __construct(
        /** Время жизни токена в секундах */
        private readonly int $expirationTime,
        private readonly string $secret
    ) {
    }

    public function createForUser(User $user): UnencryptedToken
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm = new Sha256();
        $signingKey   = InMemory::plainText($this->secret);

        $now   = new \DateTimeImmutable();
        $expiresAt = $now->modify('+ ' . $this->expirationTime . ' second');
        if ($expiresAt === false) {
            throw new \RuntimeException('Не удалось сгенерировать время протухания токена');
        }
        return $tokenBuilder
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($expiresAt)
            ->withClaim('uuid', $user->getId()->toString())
            ->getToken($algorithm, $signingKey);
    }
}
