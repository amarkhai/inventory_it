<?php

declare(strict_types=1);

namespace App\Infrastructure\Command\User;

use App\Domain\Entity\User\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\User\PasswordHashValue;
use App\Domain\ValueObject\User\UserNameValue;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:create-user')]
class CreateUserCommand extends Command
{
    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user.')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User(
            Uuid::uuid4(),
            new UserNameValue($input->getArgument('username')),
            new PasswordHashValue(\password_hash($input->getArgument('password'), PASSWORD_DEFAULT)),
            null,
            null,
            new \DateTimeImmutable()
        );

        $this->userRepository->save($user);

        $output->writeln('Id: ' . $user->getId()->toString());
        return Command::SUCCESS;
    }
}
