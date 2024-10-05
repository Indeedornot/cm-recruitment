<?php

namespace App\Controller\User;

use App\Controller\Base\BaseController;
use App\Controller\Base\ErrorHandlerType;
use App\Security\Entity\Client;
use App\Security\Factory\UserFactory;
use App\Security\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends BaseController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder,
        private EntityManagerInterface $manager,
        private UserFactory $userFactory
    ) {
    }

    #[Route("/registration", name: "app_registration")]
    public function index(Request $request): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);

        $user = $this->userFactory->createClient();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));
            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/user/registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
