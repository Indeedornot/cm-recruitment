<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Security\Entity\Admin;
use App\Security\Entity\UserRoles;
use App\Security\Factory\UserFactory;
use App\Security\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(UserRoles::ADMIN->value)]
#[Route("/admin", name: "app_admin_")]
class AdminController extends BaseController
{
    function __construct(
        private EntityManagerInterface $manager,
        private Security $security,
        private UserFactory $userFactory
    ) {
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
            $user = $this->userFactory->createAdmin();
            $form = $this->createForm(UserType::class, $user, ['require_password' => false]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Encode the new users password
                $user
                    ->setAndHashPassword($user->getPassword())
                    ->setCreatedBy($this->security->getUser());

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

    #[IsGranted(UserRoles::SUPER_ADMIN->value)]
    #[Route("/admins", name: "admins")]
    public function admins(
        Request $request,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 10
    ): Response {
        $admins = $this->manager->getRepository(Admin::class)->createQueryBuilder('a')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $this->render('pages/admin/accounts/admins.html.twig', [
            'admins' => $admins,
            'page' => $page,
            'limit' => $limit
        ]);
    }

    #[IsGranted(UserRoles::SUPER_ADMIN->value)]
    #[Route("/delete-account", name: "delete_account")]
    public function deleteAccount(Request $request): Response
    {
        $from = $request->headers->get('referer');
        return $this->redirect($from);
    }
}
