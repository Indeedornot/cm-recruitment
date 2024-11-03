<?php

namespace App\Controller\Test;

use App\Controller\Base\BaseController;
use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use App\Security\Factory\UserFactory;
use App\Security\Repository\UserRepository;
use App\Security\Services\ExtendedSecurity;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/test', name: 'app_test_')]
class TestController extends BaseController
{
    public function __construct(
        private readonly ExtendedSecurity $security,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserFactory $userFactory
    ) {
        if (!in_array($_ENV['APP_ENV'], ['dev', 'test'])) {
            throw new LogicException('This controller is only for testing purposes');
        }
    }

    #[Route(path: '/testAccount', name: 'account')]
    public function useTestAccount(
        #[MapQueryParameter] string $type
    ): Response {
        $type = match (strtolower($type)) {
            'admin' => Admin::class,
            'client' => Client::class,
            default => null,
        };
        if ($type === null) {
            return $this->renderErrorPage('Invalid account type');
        }

        $user = $this->getTestAccount($type);
        $this->security->login($user);

        return $this->redirectToRoute('app_index_index');
    }

    private function getTestAccount(string $type)
    {
        $user = $this->userRepository->findOneBy(['email' => $type . '@example.com']);
        if ($user === null) {
            $user = match ($type) {
                Admin::class => $this->userFactory->createEmptyAdmin(),
                Client::class => $this->userFactory->createEmptyClient(),
            };

            $name = explode('\\', $type);
            $name = end($name);
            $user->setEmail($name . '@example.com')->setName('Test ' . $name);
            if ($user instanceof Admin) {
                $user->setCreatedBy($user);
            }

            $user->setPassword($this->userFactory->hashPassword($user, 'password'));
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }
}
