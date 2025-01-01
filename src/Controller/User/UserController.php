<?php

namespace App\Controller\User;

use App\Contract\Exception\InvalidFieldException;
use App\Controller\Base\BaseController;
use App\Entity\ClientApplication;
use App\Entity\Questionnaire;
use App\Form\ClientApplicationType;
use App\Form\CreateQuestionnaireType;
use App\Form\PostingDisplayType;
use App\Repository\ClientApplicationRepository;
use App\Repository\PostingRepository;
use App\Security\Entity\UserRoles;
use App\Security\Factory\UserFactory;
use App\Security\Services\ExtendedSecurity;
use App\Services\Form\PaginationService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user', name: 'app_user_')]
class UserController extends BaseController
{
    function __construct(
        private PostingRepository $postingRepository,
        private EntityManagerInterface $em,
        private UserFactory $userFactory,
        private readonly ExtendedSecurity $security,
        private readonly TranslatorInterface $translator,
        private readonly PaginationService $pagination,
        private readonly ClientApplicationRepository $applicationRepository,
    ) {
    }

    #[Route("/", name: "index")]
    public function user(Request $request): Response
    {
        return $this->render('pages/user/index.html.twig', [
            'form' => $this->createForm(PostingDisplayType::class)->createView(),
        ]);
    }

    #[Route("/_search", name: "search")]
    public function search(Request $request): Response
    {
        $pagination = $this->pagination->handleRequest($request);

        $form = $this->createForm(PostingDisplayType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $qb = $this->postingRepository->getDisplayedPostingsQb($data ?? []);
        $totalItems = (clone $qb)->select('COUNT(p)')->getQuery()->getSingleScalarResult();

        $qb = $this->pagination->attachPagination($qb, $pagination);
        $postings = $qb->getQuery()->getResult();

        return $this->render('pages/user/postings.html.twig', [
            'postings' => $postings,
            ...$pagination,
            'total' => $totalItems,
        ]);
    }

    #[Route("/posting/{id}", name: "posting")]
    public function posting(Request $request, int $id): Response
    {
        $posting = $this->postingRepository->find($id);
        if (!$posting || $posting->isClosed()) {
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
        if (!$posting || $posting->isClosed()) {
            throw $this->createNotFoundException();
        }

        if (!$this->security->isAcceptedPrivacyPolicy()) {
            return $this->redirectToRoute('app_user_posting_privacy_policy', ['id' => $id]);
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
                            message: 'privacy_policy.email_taken' //TODO: Forgot password
                        );
                    } else {
                        $this->addFlash(
                            'error',
                            message: 'errors.generic'
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
                        message: $this->translator->trans('privacy_policy.failed_auto_login')
                    );

                    return $this->render('pages/user/application/index.html.twig', [
                        'posting' => $posting,
                        'form' => $form->createView(),
                    ]);
                }
            } else {
                $client = $this->security->getUser();
            }
            $clientApplication->setClient($client);

            $this->em->persist($clientApplication);
            $this->em->flush();
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('pages/user/application/index.html.twig', [
            'posting' => $posting,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/posting/{id}/application/{applicationId}", name: "posting_application")]
    public function postingApplication(Request $request, int $id, int $applicationId): Response
    {
        $posting = $this->postingRepository->find($id);
        if (!$posting) {
            throw $this->createNotFoundException();
        }

        $application = $this->applicationRepository->find($applicationId);
        if (!$application) {
            throw $this->createNotFoundException();
        }

        if ($application->getClient()->getId() !== $this->security->getUser()?->getId()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('pages/user/application/show.html.twig', [
            'posting' => $posting,
            'application' => $application,
        ]);
    }

    #[Route("/posting/privacy_policy/{id}", name: "posting_privacy_policy")]
    public function postingPrivacyPolicy(
        Request $request,
        int $id
    ): Response {
        $posting = $this->postingRepository->find($id);
        if (!$posting || $posting->isClosed()) {
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

    #[Route("/privacy_policy", name: "privacy_policy")]
    public function privacyPolicy(): Response
    {
        return $this->render('pages/user/privacy_policy.html.twig');
    }
}
