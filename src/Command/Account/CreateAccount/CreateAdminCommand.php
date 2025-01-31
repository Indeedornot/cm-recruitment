<?php

namespace App\Command\Account\CreateAccount;

use App\Security\Entity\UserRoles;
use App\Security\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-account:admin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{
    function __construct(
        private EntityManagerInterface $manager,
        private UserFactory $userFactory
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email address')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        [$email, $name, $password] = array_map(fn($arg) => $input->getArgument($arg), ['email', 'name', 'password']);

        $user = $this->userFactory->createEmptyAdmin()
            ->setEmail($email)
            ->setName($name)
            ->setPlainPassword($password)
            ->setRoles([
                UserRoles::BASE_USER->value,
                UserRoles::ADMIN->value,
                UserRoles::SUPER_ADMIN->value
            ]);
        $user->setCreatedBy($user);

        $this->manager->persist($user);
        $this->manager->flush();

        return Command::SUCCESS;
    }
}
