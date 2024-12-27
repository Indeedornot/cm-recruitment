<?php

namespace App\Controller\Admin;

use App\Controller\Base\BaseController;
use App\Controller\Base\ErrorHandlerType;
use App\Security\Entity\Admin;
use App\Security\Entity\Client;
use App\Security\Entity\UserRoles;
use App\Security\Factory\UserFactory;
use App\Security\Form\UserFormMode;
use App\Security\Form\UserType;
use App\Security\Repository\UserRepository;
use App\Security\Services\ExtendedSecurity;
use App\Services\Form\PaginationService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly ExtendedSecurity $security,
        private readonly UserFactory $userFactory,
        private readonly UserRepository $userRepository,
        private readonly PaginationService $pagination
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

        $user = $this->userFactory->createEmptyAdmin();
        $form = $this->createForm(UserType::class, $user, ['require_password' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();
            $form = $this->createForm(UserType::class, $this->userFactory->createEmptyAdmin());
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
        $form = $this->createForm(UserType::class, $user, ['mode' => UserFormMode::EDIT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('resetPassword')->isClicked()) {
                $this->userFactory->resetPassword($user);
            }

            $this->manager->persist($user);
            $this->manager->flush();
            $form = $this->createForm(UserType::class, $this->userRepository->find($id), [
                'mode' => UserFormMode::EDIT
            ]);
        }

        return $this->render('pages/admin/accounts/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[IsGranted(UserRoles::SUPER_ADMIN->value)]
    #[Route("/admins", name: "admins")]
    public function admins(
        Request $request,
    ): Response {
        $pagination = $this->pagination->handleRequest($request);
        $pagination = $this->pagination->getPagination(Admin::class, $pagination);
        $pagination['users'] = $pagination['items'];
        unset($pagination['items']);

        return $this->render('pages/admin/accounts/admins.html.twig', $pagination);
    }

    #[Route("/users", name: "users")]
    public function users(
        Request $request,
    ): Response {
        $pagination = $this->pagination->handleRequest($request);
        $pagination = $this->pagination->getPagination(Client::class, $pagination);
        $pagination['users'] = $pagination['items'];
        unset($pagination['items']);

        return $this->render('pages/admin/accounts/users.html.twig', $pagination);
    }


    #[IsGranted(UserRoles::SUPER_ADMIN->value)]
    #[Route("/delete-account", name: "delete_account")]
    public function deleteAccount(Request $request): Response
    {
        $from = $request->headers->get('referer');
        return $this->redirect($from);
    }
}
