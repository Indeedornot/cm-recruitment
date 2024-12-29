<?php

namespace App\Controller\User;

use App\Controller\Base\BaseController;
use App\Controller\Base\ErrorHandlerType;
use App\Security\Factory\UserFactory;
use App\Security\Form\UserType;
use App\Security\Repository\UserRepository;
use App\Security\Services\ExtendedSecurity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends BaseController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private UserFactory $userFactory,
        private readonly UserRepository $userRepository,
        private readonly ExtendedSecurity $security
    ) {
    }

    #[Route("/registration", name: "app_registration")]
    public function index(Request $request): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);

        $user = $this->userFactory->createEmptyClient()
            ->disable();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();
            $this->addFlash('success', 'security.register_success');
        }

        return $this->render('pages/user/registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/registration/confirm", name: "app_registration_confirm")]
    public function confirm(#[MapQueryParameter] string $id): Response
    {
        $user = $this->userRepository->find($this->security->decodeValue($id));
        if ($user === null || !$user->isDisabled()) {
            throw $this->createNotFoundException();
        }

        $user->enable();
        $this->manager->flush();

        return $this->render('pages/user/registration/confirm.html.twig');
    }
}
