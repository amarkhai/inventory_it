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
use Tests\TestCase;

/**
 * @group list-items
 */
class ListItemsActionTest extends TestCase
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

        $token = $jwtTokenCreator->createForUser($user1);

        // Отправляем запрос до добавления items в БД
        $request = $this->createRequest('GET', '/items')
            ->withHeader('Authorization', 'Bearer ' . $token->toString())
        ;
        $response = $app->handle($request);
        $payload = \json_decode((string) $response->getBody(), true);
        $statusCodeBeforeInserting = $payload['statusCode'];
        $itemsInResponseBeforeInserting = $payload['data'];

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

        $itemRepository->insert($item1, $temporaryId1, null);
        $itemRepository->insert($item2, $temporaryId2, null);

        // Отправляем запрос от первого пользователя
        $request = $this->createRequest('GET', '/items')
            ->withHeader('Authorization', 'Bearer ' . $token->toString())
        ;
        $response = $app->handle($request);
        $payload = \json_decode((string) $response->getBody(), true);

        $statusCodeAfterInserting = $payload['statusCode'];
        $itemsInResponseAfterInserting = $payload['data'];
        $xTotalCountAfterInserting = $response->getHeaderLine('X-Total-Count');
        //@todo покрыть тестами пагинацию

        // Код ответа в обоих запросах должен быть 200
        $this->assertEquals(200, $statusCodeBeforeInserting);
        $this->assertEquals(200, $statusCodeAfterInserting);

        // До добавления items в БД при запросе должен вернуться пустой список items
        $this->assertCount(0, $itemsInResponseBeforeInserting);

        // После добавления должен появиться один item в ответе
        $this->assertCount(1, $itemsInResponseAfterInserting);
        // ... и в заголовке X-Total-Count
        $this->assertEquals(1, $xTotalCountAfterInserting);

        // И этот item должен быть именно тем, у которого owner_id - пользователь, отправивший запрос
        // Второй item не должен появиться в ответе
        $item = $itemsInResponseAfterInserting[0];
        $this->assertEquals($item1->getName()->getValue(), $item['name']);
        $this->assertEquals($item1->getDescription()->getValue(), $item['description']);
        $this->assertEquals($item1->getOwnerId()->toString(), $item['owner_id']);

        $pdo->rollBack();
    }
}
