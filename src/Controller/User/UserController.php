<?php

namespace App\Controller\User;

use App\Contract\Exception\InvalidFieldException;
use App\Controller\Base\BaseController;
use App\Entity\ClientApplication;
use App\Form\ClientApplicationType;
use App\Repository\PostingRepository;
use App\Security\Entity\UserRoles;
use App\Security\Factory\UserFactory;
use App\Security\Services\ExtendedSecurity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'app_user_')]
class UserController extends BaseController
{
    function __construct(
        private PostingRepository $postingRepository,
        private EntityManagerInterface $em,
        private UserFactory $userFactory,
        private readonly ExtendedSecurity $security
    ) {
    }

    #[Route("/", name: "index")]
    public function user(Request $request): Response
    {
        return $this->render('pages/user/index.html.twig', [
            'postings' => $this->postingRepository->findBy(['disabledAt' => null], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route("/posting/{id}", name: "posting")]
    public function posting(Request $request, int $id): Response
    {
        $posting = $this->postingRepository->find($id);
        if (!$posting) {
            throw $this->createNotFoundException();
        }
        return $this->render('pages/user/posting.html.twig', [
            'posting' => $posting,
        ]);
    }

    #[Route("/posting/{id}/apply", name: "posting_apply")]
    public function postingApply(Request $request, int $id): Response
    {
        $posting = $this->postingRepository->find($id);
        if (!$posting) {
            throw $this->createNotFoundException();
        }

        if (!$this->security->isAcceptedPrivacyPolicy()) {
            return $this->redirectToRoute('app_user_posting_privacy_policy', [
                'id' => $posting->getId(),
            ]);
        }

        $clientApplication = (new ClientApplication())->setPosting($posting);
        $form = $this->createForm(ClientApplicationType::class, $clientApplication);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            if (!$this->isLoggedIn()) {
                try {
                    $client = $this->userFactory->createTemporaryClient($clientApplication);
                } catch (Exception $e) {
                    if ($e instanceof InvalidFieldException) {
                        $this->addFlash(
                            'error',
                            message: <<<'EOF'
Podany adres email jest już zajęty.
<br/>
<br/>
&bull; Spróbuj zalogować się na swoje konto lub skorzystać z opcji "Zapomniałem hasła".
EOF //TODO: Forgot password
                        );
                    } else {
                        $this->addFlash(
                            'error',
                            message: <<<'EOF'
Nie udało się stworzyć konta na podstawie podanych danych.
Proszę spróbować ponownie.

Jeśli problem będzie się powtarzał, proszę skontaktować się z administratorem.
EOF
                        );
                    }
                    return $this->render('pages/user/application/index.html.twig', [
                        'posting' => $posting,
                        'form' => $form->createView(),
                    ]);
                }

                try {
                    $this->security->login($client);
                } catch (Exception $e) {
                    $this->addFlash(
                        'error',
                        message: <<<'EOF'
Nie udało się zalogować na nowo utworzone konto.
<br/>
<br/>

&bull; Na podany adres email zostało wysłane nowe hasło.
Proszę zalogować się na swoje konto przy użyciu nowego hasła i ponownie spróbować aplikować na ogłoszenie.
EOF
                    );

                    return $this->render('pages/user/application/index.html.twig', [
                        'posting' => $posting,
                        'form' => $form->createView(),
                    ]);
                }

                $clientApplication->setClient($client);
            }

            $this->em->persist($clientApplication);
            $this->em->flush();
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('pages/user/application/index.html.twig', [
            'posting' => $posting,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/posting/privacy_policy/{id}", name: "posting_privacy_policy")]
    public function postingPrivacyPolicy(
        Request $request,
        int $id
    ): Response {
        $posting = $this->postingRepository->find($id);
        if (!$posting) {
            throw $this->createNotFoundException();
        }

        if ($request->get('ACCEPT_PRIVACY_POLICY')) {
            $this->security->setAcceptedPrivacyPolicy(true);
            return $this->redirectToRoute('app_user_posting_apply', [
                'id' => $posting->getId(),
            ]);
        }

        return $this->render('pages/user/application/privacy_policy.html.twig', [
            'posting' => $posting,
        ]);
    }
}
