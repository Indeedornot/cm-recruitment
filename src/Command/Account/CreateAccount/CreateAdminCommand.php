<?php

namespace App\Command\Account\CreateAccount;

use App\Security\Entity\Admin;
use App\Security\Entity\UserRoles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-account:admin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{
    function __construct(private readonly UserPasswordHasherInterface $passwordEncoder, private EntityManagerInterface $manager)
    {
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

        $user = new Admin();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
        $user->setRoles([UserRoles::BASE_USER->value, UserRoles::ADMIN->value]);
        $user->setCreatedBy($user);

        $this->manager->persist($user);
        $this->manager->flush();

        return Command::SUCCESS;
    }
}
