<?php

namespace App\Controller\Admin;

use App\Controller\Base\BaseController;
use App\Controller\Base\ErrorHandlerType;
use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use App\Security\Entity\UserRoles;
use App\Security\Factory\UserFactory;
use App\Security\Form\UserType;
use App\Security\Repository\UserRepository;
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
        private readonly EntityManagerInterface $manager,
        private readonly Security $security,
        private readonly UserFactory $userFactory,
        private readonly UserRepository $userRepository
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
        $this->setErrorHandler(ErrorHandlerType::FORM);

        $user = $this->userFactory->createAdmin();
        $form = $this->createForm(UserType::class, $user, ['require_password' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setAndHashPassword($user->getPassword());

            $this->manager->persist($user);
            $this->manager->flush();
            $form = $this->createForm(UserType::class);
        }

        return $this->render('pages/admin/accounts/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[IsGranted(UserRoles::SUPER_ADMIN->value)]
    #[Route("/edit-account", name: "edit_account")]
    public function editAccount(Request $request): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);

        $id = $request->query->get('id');
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserType::class, $user, ['mode' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();
            $form = $this->createForm(UserType::class);
        }

        return $this->render('pages/admin/accounts/manage.html.twig', [
            'form' => $form->createView()
        ]);
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

    #[Route("/users", name: "users")]
    public function users(
        Request $request,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] int $limit = 10
    ): Response {
        $users = $this->manager->getRepository(Client::class)->createQueryBuilder('a')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $this->render('pages/admin/accounts/users.html.twig', [
            'users' => $users,
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
