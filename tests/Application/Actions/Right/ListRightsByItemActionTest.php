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
        // todo
        $this->markTestIncomplete('Нужно доработать сам метод');

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

        $pdo->rollBack();
    }
}
