<?php

namespace App\Infrastructure\Command\Faker;

use App\Domain\Entity\Item\Item;
use App\Domain\Entity\Item\ItemRepository;
use App\Domain\Entity\User\User;
use App\Domain\Entity\User\UserRepository;
use App\Domain\ValueObject\Item\DescriptionValue;
use App\Domain\ValueObject\Item\ItemStatusEnum;
use App\Domain\ValueObject\Item\NameValue;
use App\Domain\ValueObject\Item\OwnerIdValue;
use App\Domain\ValueObject\Item\StatusValue;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:faker-fill')]
class FillFakesCommand extends Command
{
    protected static $defaultDescription = 'Заполнение БД фейковыми данными.';
    protected Generator $faker;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ItemRepository $itemRepository
    ) {
        $this->faker = Factory::create();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('usersNumber', InputArgument::OPTIONAL, 'Кол-во users', 3)
            ->addArgument('itemsNumber', InputArgument::OPTIONAL, 'Количество items', 100)
            ->addArgument('treesPerUser', InputArgument::OPTIONAL, 'Количество деревьев на пользователя', 3)
            ->addArgument('maxDeepness', InputArgument::OPTIONAL, 'Максимальная глубина деревьев', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->createUsers($input->getArgument('usersNumber'));
        $output->writeln('Создано пользователей: ' . count($users));

        $items = $this->createItems(
            $users,
            $input->getArgument('itemsNumber'),
            $input->getArgument('treesPerUser'),
            $input->getArgument('maxDeepness')
        );

        $output->writeln('Создано items: ' . count($items));


        $output->writeln('Выполнено.');
        return Command::SUCCESS;
    }

    /**
     * @param User[] $users
     * @param int $itemsNumber
     * @param int $treesPerUser
     * @param int $maxDeepness
     * @return Item[]
     */
    private function createItems(
        array $users,
        int $itemsNumber,
        int $treesPerUser,
        int $maxDeepness
    ): array {
        $createdItems = [];

        // создаем корневые узлы деревьев
        for ($i = 0; $i < $treesPerUser; $i++) {
            foreach ($users as $user) {
                $createdItems[] = $this->createItem($user->getId());
            }
        }

        // добавляем элементы, чтобы деревья получились рандомной глубины, но < $maxDeepness
        foreach ($createdItems as $rootItem) {
            $parentPath = $rootItem->getPath();
            $curDeepness = rand(0, $maxDeepness - 1);
            for ($j = 0; $j < $curDeepness; $j++) {
                $newItem = $this->createItem(
                    $rootItem->getOwnerId()->getValue(),
                    $parentPath->getValue()
                );
                $parentPath = $newItem->getPath();
                $createdItems[] = $newItem;
            }
        }

        $prevItems = $createdItems;
        while (count($createdItems) < $itemsNumber) {
            $parent = $prevItems[rand(0, count($prevItems) - 1)];
            $newItem = $this->createItem(
                $parent->getOwnerId()->getValue(),
                $parent->getPath()->getValue()
            );
            $createdItems[] = $newItem;
        }

        return $createdItems;
    }

    private function createItem(UuidInterface $ownerId, string $parentPath = null): Item
    {
        $item = new Item();
        $item->setName(new NameValue($this->faker->name));
        $item->setStatus(new StatusValue(ItemStatusEnum::active));
        $item->setDescription(new DescriptionValue($this->faker->text));
        $item->setOwnerId(new OwnerIdValue($ownerId));

        $itemMap = $this->itemRepository->insert(
            $item,
            UuidV4::uuid4()->toString(),
            $parentPath
        );

        $item->setPath($itemMap->getPath());

        return $item;
    }

    private function createUsers(int $usersNumber): array
    {
        $userIds = array_map(
            fn($_): UuidInterface => Uuid::uuid4(),
            array_fill(0, $usersNumber, null)
        );

        $res = [];
        foreach ($userIds as $userId) {
            $user = new User(
                $userId,
                $this->faker->name,
                \password_hash('123123', PASSWORD_DEFAULT),
                new \DateTimeImmutable()
            );
            $this->userRepository->save($user);
            $res[] = $user;
        }
        return $res;
    }
}
