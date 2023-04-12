<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Item;

use App\Application\UseCase\Auth\JWTTokenCreator;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\User\User;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemIdValue;
use App\Domain\ValueObject\Item\ItemNameValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\User\FirstNameValue;
use App\Domain\ValueObject\User\LastNameValue;
use App\Domain\ValueObject\User\PasswordHashValue;
use App\Domain\ValueObject\User\UserNameValue;
use DI\Container;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group create-item
 */
class CreateItemActionTest extends TestCase
{
    private \Faker\Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testAction(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $pdo = $container->get(\PDO::class);
        $pdo->beginTransaction();

        $user = new User(
            Uuid::uuid4(),
            new UserNameValue($this->faker->asciify('**********')),
            new PasswordHashValue(\password_hash($this->faker->asciify('**********'), PASSWORD_DEFAULT)),
            new FirstNameValue($this->faker->firstName),
            new LastNameValue($this->faker->lastName),
            new \DateTimeImmutable()
        );
        $userRepository = $container->get(UserRepositoryInterface::class);
        $itemRepository = $container->get(ItemRepositoryInterface::class);
        $jwtTokenCreator = $container->get(JWTTokenCreator::class);
        $userRepository->save($user);
        $token = $jwtTokenCreator->createForUser($user);

        $temporaryId = Uuid::uuid4();
        $userEmail = $this->faker->email();
        $type = \random_int(1, 10) > 5 ? 'ro' : 'rw';

        $item = new Item(
            null,
            null,
            ItemStatusEnum::active,
            Uuid::uuid4(),
            new ItemNameValue($this->faker->asciify('**********')),
            new ItemDescriptionValue($this->faker->asciify('********************')),
        );

        $request = $this->createRequest('POST', '/items')
            ->withParsedBody([
                'temporary_id' => $temporaryId->toString(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'parent_path' => '',
                'rights' => [
                    [
                        'user' => [
                            'email' => $userEmail,
                        ],
                        'type' => $type,
                    ]
                ],
            ])
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $token->toString())
        ;

        $response = $app->handle($request);

        $payload = json_decode((string) $response->getBody(), true);
        $actualTemporaryId = $payload['data']['temporary_id'];
        $itemId = $payload['data']['id'];
        $status = $payload['statusCode'];

        $item = $itemRepository->findOneById(new ItemIdValue($itemId));

        $this->assertEquals(200, $status);
        $this->assertEquals($temporaryId, $actualTemporaryId);
        $this->assertNotNull($item);

        $pdo->rollBack();
    }
}