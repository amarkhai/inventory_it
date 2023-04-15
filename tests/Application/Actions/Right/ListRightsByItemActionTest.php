<?php

declare(strict_types=1);

namespace Application\Actions\Right;

use App\Application\UseCase\Auth\JWTTokenCreator;
use App\Domain\DomainException\DomainWrongEntityParamException;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Right\Right;
use App\Domain\Entity\User\User;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\RightRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemNameValue;
use App\Domain\ValueObject\Item\ItemPathValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Right\RightTypeEnum;
use App\Domain\ValueObject\User\FirstNameValue;
use App\Domain\ValueObject\User\LastNameValue;
use App\Domain\ValueObject\User\PasswordHashValue;
use App\Domain\ValueObject\User\UserNameValue;
use DI\Container;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @group list-right-by-item
 */
class ListRightsByItemActionTest extends TestCase
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

        // Создаем инстансы необходимых сервисов
        $userRepository = $container->get(UserRepositoryInterface::class);
        $itemRepository = $container->get(ItemRepositoryInterface::class);
        $rightRepository = $container->get(RightRepositoryInterface::class);
        $jwtTokenCreator = $container->get(JWTTokenCreator::class);

        // Создадим двух пользователей
        $user1 = new User(
            Uuid::uuid4(),
            new UserNameValue($this->faker->asciify('**********')),
            new PasswordHashValue(\password_hash($this->faker->asciify('**********'), PASSWORD_DEFAULT)),
            new FirstNameValue($this->faker->firstName),
            new LastNameValue($this->faker->lastName),
            new \DateTimeImmutable()
        );
        $user2 = new User(
            Uuid::uuid4(),
            new UserNameValue($this->faker->asciify('**********')),
            new PasswordHashValue(\password_hash($this->faker->asciify('**********'), PASSWORD_DEFAULT)),
            new FirstNameValue($this->faker->firstName),
            new LastNameValue($this->faker->lastName),
            new \DateTimeImmutable()
        );

        $item1 = new Item(
            null,
            null,
            ItemStatusEnum::active,
            Uuid::uuid4(),
            new ItemNameValue($this->faker->asciify('**********')),
            new ItemDescriptionValue($this->faker->asciify('********************')),
        );
        $item1->setOwnerId($user1->getId());
        $temporaryId1 = Uuid::uuid4();

        $item2 = new Item(
            null,
            null,
            ItemStatusEnum::active,
            Uuid::uuid4(),
            new ItemNameValue($this->faker->asciify('**********')),
            new ItemDescriptionValue($this->faker->asciify('********************')),
        );
        $item2->setOwnerId($user2->getId());
        $temporaryId2 = Uuid::uuid4();

        $userRepository->save($user1);
        $userRepository->save($user2);
        $savedItem1 = $itemRepository->insert($item1, $temporaryId1, null);
        $savedItem2 = $itemRepository->insert($item2, $temporaryId2, null);

        $tokenForUser1 = $jwtTokenCreator->createForUser($user1);
        $tokenForUser2 = $jwtTokenCreator->createForUser($user2);

        // Запрашиваем список rights до добавления
        $requestBody = ['item_id' => $savedItem1->getId()];
        $request = $this->createRequest('GET', '/rights/by-item')
            ->withHeader('Authorization', 'Bearer ' . $tokenForUser1->toString())
            ->withParsedBody($requestBody)
            ->withHeader('Content-Type', 'application/json')
        ;
        $response = $app->handle($request);
        $payload = \json_decode((string) $response->getBody(), true);
        $statusCodeBeforeInserting = $payload['statusCode'];
        $rightsInResponseBeforeInserting = $payload['data'];

        // добавляем rights
        $right1Id = Uuid::uuid4();
        $right1 = new Right(
            $right1Id,
            $savedItem1->getPath(),
            $user1->getId(),
            RightTypeEnum::ro
        );

        $right2Id = Uuid::uuid4();
        $right2 = new Right(
            $right2Id,
            $savedItem1->getPath(),
            $user1->getId(),
            RightTypeEnum::rw,
        );

        $rightRepository->insert($right1);
        $rightRepository->insert($right2);

        // Запрашиваем список rights после добавления
        $requestBody = ['item_id' => $savedItem1->getId()];
        $request = $this->createRequest('GET', '/rights/by-item')
            ->withHeader('Authorization', 'Bearer ' . $tokenForUser1->toString())
            ->withParsedBody($requestBody)
            ->withHeader('Content-Type', 'application/json')
        ;
        $response = $app->handle($request);
        $payload = \json_decode((string) $response->getBody(), true);
        $item1RightsInResponseAfterInserting = $payload['data'];

        $requestBody = ['item_id' => $savedItem2->getId()];
        $request = $this->createRequest('GET', '/rights/by-item')
            ->withHeader('Authorization', 'Bearer ' . $tokenForUser2->toString())
            ->withParsedBody($requestBody)
            ->withHeader('Content-Type', 'application/json')
        ;
        $response = $app->handle($request);
        $payload = \json_decode((string) $response->getBody(), true);
        $item2RightsInResponseAfterInserting = $payload['data'];

        $this->assertEquals(200, $statusCodeBeforeInserting);
        $this->assertEquals([], $rightsInResponseBeforeInserting);
        $this->assertEquals([], $item2RightsInResponseAfterInserting);
        $this->assertCount(2, $item1RightsInResponseAfterInserting);

        $this->assertContains(
            [
                'id' => $right1Id->toString(),
                'path' => $savedItem1->getPath()->getValue(),
                'user_id' => $user1->getId()->toString(),
                'type' => RightTypeEnum::ro->getValue()
            ],
            $item1RightsInResponseAfterInserting
        );

        // проверяем, что нельзя получить список прав на чужую item
        $requestBody = ['item_id' => $savedItem1->getId()];
        $request = $this->createRequest('GET', '/rights/by-item')
            ->withHeader('Authorization', 'Bearer ' . $tokenForUser2->toString())
            ->withParsedBody($requestBody)
            ->withHeader('Content-Type', 'application/json')
        ;
        $this->expectException(DomainWrongEntityParamException::class);
        $app->handle($request);

        $pdo->rollBack();
    }
}
