<?php

declare(strict_types=1);

namespace Application\Actions\Item;

use App\Application\UseCase\Auth\JWTTokenCreator;
use App\Domain\Entity\Item\Item;
use App\Domain\Entity\User\User;
use App\Domain\Repository\ItemRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Item\ItemDescriptionValue;
use App\Domain\ValueObject\Item\ItemNameValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\User\FirstNameValue;
use App\Domain\ValueObject\User\LastNameValue;
use App\Domain\ValueObject\User\PasswordHashValue;
use App\Domain\ValueObject\User\UserNameValue;
use DI\Container;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Slim\Exception\HttpNotFoundException;
use Tests\TestCase;

/**
 * @group view-item
 */
class ViewItemActionTest extends TestCase
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

        $userRepository->save($user1);
        $userRepository->save($user2);

        // Создаем два items - по одному на каждого юзера
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

        $savedItem1 = $itemRepository->insert($item1, $temporaryId1, null);
        $savedItem2 = $itemRepository->insert($item2, $temporaryId2, null);

        // Отправляем запрос от первого пользователя
        $token = $jwtTokenCreator->createForUser($user1);
        $request1 = $this->createRequest('GET', '/items/' . $savedItem1->getId()->getValue())
            ->withHeader('Authorization', 'Bearer ' . $token->toString())
        ;
        $response = $app->handle($request1);
        $payload = \json_decode((string) $response->getBody(), true);

        // код ответа должен быть 200
        $this->assertEquals(200, $payload['statusCode']);

        // Ответ должен состоять из полей item1
        $itemInResponse = $payload['data'];
        $this->assertEquals($savedItem1->getId()->getValue(), $itemInResponse['id']);
        $this->assertEquals($item1->getName()->getValue(), $itemInResponse['name']);
        $this->assertEquals($item1->getDescription()->getValue(), $itemInResponse['description']);
        $this->assertEquals($item1->getOwnerId()->toString(), $itemInResponse['owner_id']);

        // Проверяем, что второй item, принадлежащий другому пользователю, мы получить не можем
        $this->expectException(HttpNotFoundException::class);
        $request2 = $this->createRequest('GET', '/items/' . $savedItem2->getId()->getValue())
            ->withHeader('Authorization', 'Bearer ' . $token->toString())
        ;
        $app->handle($request2);

        $pdo->rollBack();
    }
}
