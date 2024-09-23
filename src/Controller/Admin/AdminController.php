<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\User;
use App\Entity\UserRoles;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(UserRoles::ADMIN->value)]
#[Route("/admin", name: "app_admin_")]
class AdminController extends AbstractController
{
    function __construct(private readonly UserPasswordHasherInterface $passwordEncoder, private EntityManagerInterface $manager, private Security $security)
    {
    }

    #[Route("/", name: "index")]
    public function admin(Request $request): Response
    {
        return $this->render('pages/admin/index.html.twig');
    }

    #[IsGranted(UserRoles::SUPER_ADMIN->value)]
    #[Route("/create-account", name: "create_account")]
    public function createAccount(Request $request): Response
    {
        try {
            $user = new Admin();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Encode the new users password
                $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));
                $user->setRoles([UserRoles::BASE_USER->value, UserRoles::ADMIN->value]);
                $user->setCreatedBy($this->security->getUser());

                $this->manager->persist($user);
                $this->manager->flush();

                return $this->render('pages/admin/accounts/create.html.twig');
            }

            return $this->render('pages/admin/accounts/create.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $e) {
            return $this->render('errors/index.html.twig', [
                'error' => $e
            ]);
        }
    }
}
