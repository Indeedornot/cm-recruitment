<?php

namespace App\Controller\Admin;

use App\Controller\Base\BaseController;
use App\Controller\Base\ErrorHandlerType;
use App\Entity\Posting;
use App\Form\ClientApplicationType;
use App\Form\PostingDisplayType;
use App\Form\PostingType;
use App\Repository\ClientApplicationRepository;
use App\Repository\PostingRepository;
use App\Security\Entity\Client;
use App\Security\Entity\UserRoles;
use App\Security\Services\ExtendedSecurity;
use App\Services\Form\PaginationService;
use App\Services\Posting\ClientApplicationHandler;
use App\Services\Posting\QuestionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted(UserRoles::ADMIN->value)]
#[Route("/admin/posting", name: "app_admin_posting_")]
class PostingController extends BaseController
{
    function __construct(
        private readonly EntityManagerInterface $manager,
        private PostingRepository $postingRepository,
        private EntityManagerInterface $em,
        private readonly ExtendedSecurity $extendedSecurity,
        private readonly PaginationService $pagination,
        private readonly ClientApplicationRepository $applicationRepository,
        private readonly QuestionService $questionService,
        private readonly ClientApplicationHandler $clientApplicationHandler
    ) {
    }

    #[Route("/{id}", name: "show", requirements: ['id' => '\d+'])]
    public function show(Request $request, int $id): Response
    {
        $posting = $this->postingRepository->find($id);
        return $this->render('pages/admin/posting/show.html.twig', [
            'posting' => $posting,
            'questionService' => $this->questionService,
            'clientApplicationHandler' => $this->clientApplicationHandler,
        ]);
    }

    #[Route("/create", name: "create")]
    public function create(Request $request): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);

        $posting = (new Posting())->setCreatedBy($this->getAdmin());
        return $this->handlePostingForm($posting, $request);
    }

    private function handlePostingForm(Posting $posting, Request $request, array $params = []): Response
    {
        $form = $this->createForm(PostingType::class, $posting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($posting);
            $this->em->flush();
            if (!empty($params['recreate_form'])) {
                $form = $this->createForm(PostingType::class, $posting);
            } else {
                $form = $this->createForm(PostingType::class);
            }

            $this->addFlash('success', new TranslatableMessage('components.posting.form.success'));
        }

        return $this->render('pages/admin/posting/manage/manage.html.twig', array_merge([
            'form' => $form->createView(),
        ], $params));
    }

    #[Route("/edit/{id}", name: "edit", requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);
        $posting = $this->postingRepository->find($id);

        if (!$posting->canEdit($this->getAdmin())) {
            throw $this->createAccessDeniedException();
        }

        return $this->handlePostingForm($posting, $request, ['recreate_form' => true]);
    }

    #[Route("/delete/{id}", name: "delete", requirements: ['id' => '\d+'])]
    public function delete(Request $request, int $id): Response
    {
        $posting = $this->postingRepository->find($id);
        if (!$posting->canEdit($this->getAdmin())) {
            throw $this->createAccessDeniedException();
        }

        if (!$posting->getApplications()->isEmpty()) {
            $this->addFlash('error', new TranslatableMessage('components.posting.form.error.delete_with_candidates'));
            return $this->redirectToRoute('app_admin_posting_index');
        }

        if (!$request->getSession()->get("confirm_delete_$id")) {
            $request->getSession()->set("confirm_delete_$id", true);
            $this->addFlash('warning', new TranslatableMessage('common.are_you_sure'));
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        $posting->disable();

        $this->em->flush();
        $this->addFlash('success', new TranslatableMessage('components.posting.form.success'));
        return $this->redirectToRoute('app_admin_posting_index');
    }

    #[Route("/", name: "index")]
    public function users(Request $request): Response
    {
        $pagination = $this->pagination->handleRequest($request);
        $form = $this->createForm(PostingDisplayType::class);
        return $this->render('pages/admin/posting/index.html.twig', [...$pagination, 'form' => $form]);
    }

    #[Route("/_search", name: "search")]
    public function search(Request $request): Response
    {
        $form = $this->createForm(PostingDisplayType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $pagination = $this->pagination->handleRequest($request);
        $qb = $this->postingRepository->getAdminDisplayPostingsQb($data ?? []);
        $totalItems = (clone $qb)->select('COUNT(p)')->getQuery()->getSingleScalarResult();

        $qb = $this->pagination->attachPagination($qb, $pagination);
        $postings = $qb->getQuery()->getResult();

        return $this->render('pages/admin/posting/postings.html.twig', [
            'postings' => $postings,
            ...$pagination,
            'total' => $totalItems,
        ]);
    }

    #[Route('/{id}/application/{applicationId}', name: 'application', requirements: [
        'id' => '\d+',
        'applicationId' => '\d+'
    ])]
    public function showApplication(
        Request $request,
        int $id,
        int $applicationId
    ): Response {
        $application = $this->applicationRepository->find($applicationId);
        $form = $this->createForm(ClientApplicationType::class, $application);
        if (!$application || $application->getPosting()->getId() !== $id) {
            throw $this->createNotFoundException();
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'common.success');
        }

        return $this->render('pages/admin/posting/application.html.twig', [
            'application' => $application,
            'form' => $form->createView(),
            'posting' => $application->getPosting(),
        ]);
    }

//    delete application
    #[Route('/{id}/application/{applicationId}/delete', name: 'application_delete', requirements: [
        'id' => '\d+',
        'applicationId' => '\d+'
    ])]
    public function deleteApplication(
        Request $request,
        int $id,
        int $applicationId
    ): Response {
        $application = $this->applicationRepository->find($applicationId);
        if (!$application || $application->getPosting()->getId() !== $id) {
            throw $this->createNotFoundException();
        }

        if (!$request->getSession()->get("confirm_delete_application_$applicationId")) {
            $request->getSession()->set("confirm_delete_application_$applicationId", true);
            $this->addFlash('warning', 'common.are_you_sure');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        $this->manager->remove($application);
        $this->manager->flush();
        $this->addFlash('success', 'common.success');
        return $this->redirectToRoute('app_admin_posting_show', ['id' => $id]);
    }
}
