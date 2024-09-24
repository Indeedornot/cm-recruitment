<?php

namespace App\Controller\User;

use App\Entity\Client;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordEncoder, private EntityManagerInterface $manager)
    {
    }

    #[Route("/registration", name: "app_registration")]
    public function index(Request $request): Response
    {
        try {

            $user = new Client();

            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));
                $this->manager->persist($user);
                $this->manager->flush();

                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/registration/index.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $e) {
            return $this->render('errors/index.html.twig', [
                'error' => $e
            ]);
        }
    }
}
