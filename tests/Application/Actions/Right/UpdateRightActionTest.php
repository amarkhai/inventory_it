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
 * @group update-right
 */
class UpdateRightActionTest extends TestCase
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

        // Создадим пару пользователей, items и rights
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

        $right1 = new Right(
            Uuid::uuid4(),
            new ItemPathValue($savedItem1->getPath()->getValue()),
            $user1->getId(),
            \random_int(1, 10) > 5 ? RightTypeEnum::ro : RightTypeEnum::rw
        );
        $rightRepository->insert($right1);

        $right2 = new Right(
            Uuid::uuid4(),
            new ItemPathValue($savedItem2->getPath()->getValue()),
            $user2->getId(),
            \random_int(1, 10) > 5 ? RightTypeEnum::ro : RightTypeEnum::rw
        );
        $rightRepository->insert($right2);

        // Отправляем запрос на изменение right, принадлежащего пользователю, который запрос выполняет
        $token = $jwtTokenCreator->createForUser($user1);
        $newType = $right1->getType() === RightTypeEnum::rw ? RightTypeEnum::ro : RightTypeEnum::rw;
        $requestBody = [
            'path' => $savedItem1->getId()->getValue(),
            'user_id' => $user2->getId()->toString(),
            'type' =>  $newType->getValue(),
        ];

        $request = $this->createRequest('PUT', '/rights/' . $right1->getId()->toString())
            ->withParsedBody($requestBody)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $token->toString())
        ;

        $response = $app->handle($request);
        $payload = json_decode((string) $response->getBody(), true);

        // Код ответа должен быть 200, data === true
        $this->assertEquals(200, $payload['statusCode']);
        $this->assertTrue($payload['data']);

        // Проверяем, что right изменился в БД
        $updatedRight1 = $rightRepository->findOneById($right1->getId());
        $this->assertEquals($newType, $updatedRight1->getType());
        $this->assertEquals($requestBody['user_id'], $updatedRight1->getUserId()->toString());

        // пробуем изменить right, принадлежащий другому пользователю. Ожидаем ошибку
        $requestBody = [
            'path' => $savedItem2->getId()->getValue(),
            'user_id' => $user2->getId()->toString(),
            'type' =>  $newType->getValue(),
        ];
        $request = $this->createRequest('PUT', '/rights/' . $right2->getId()->toString())
            ->withParsedBody($requestBody)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $token->toString())
        ;
        $this->expectException(DomainWrongEntityParamException::class);
        $app->handle($request);

        $pdo->rollBack();
    }
}
