<?php

namespace App\Controller\Security;

use App\Controller\Base\BaseController;
use App\Security\Entity\User;
use App\Security\Entity\UserRoles;
use App\Security\Factory\UserFactory;
use App\Security\Form\ForgotPasswordType;
use App\Security\Form\UserFormMode;
use App\Security\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    function __construct(private EntityManagerInterface $em, private UserFactory $userFactory)
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    #[IsGranted(UserRoles::BASE_USER->value)]
    #[Route(path: '/reset-password', name: 'app_reset_password')]
    public function forcePasswordChange(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, ['mode' => UserFormMode::PASSWORD_CHANGE]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->userFactory->hashPassword($user, $user->getPlainPassword()));
            $this->em->flush();
            return $this->redirectToRoute('app_index_index');
        }
        $this->addFlash('warning', 'security.form.force_password_change.alert');

        return $this->render('security/login/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted(UserRoles::BASE_USER->value)]
    #[Route(path: '/edit-account', name: 'app_edit_account')]
    public function editAccount(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, ['mode' => UserFormMode::EDIT]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('app_index_index');
        }

        return $this->render('security/login/edit-account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'security.form.forgot_password.success');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/login/forgot-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
