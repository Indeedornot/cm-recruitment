<?php

namespace App\Controller\Admin;

use App\Controller\Base\BaseController;
use App\Controller\Base\ErrorHandlerType;
use App\Entity\Posting;
use App\Form\PostingType;
use App\Repository\PostingRepository;
use App\Security\Entity\Client;
use App\Security\Entity\UserRoles;
use App\Security\Services\ExtendedSecurity;
use App\Services\Form\PaginationService;
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
        private readonly PaginationService $pagination
    ) {
    }

    #[Route("/{id}", name: "show", requirements: ['id' => '\d+'])]
    public function show(Request $request, int $id): Response
    {
        $posting = $this->postingRepository->find($id);
        return $this->render('pages/admin/posting/show.html.twig', [
            'posting' => $posting,
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
            $form = $this->createForm(PostingType::class);

            $this->addFlash('success', new TranslatableMessage('components.posting.form.success'));
        }

        return $this->render('pages/admin/posting/manage.html.twig', array_merge([
            'form' => $form->createView(),
        ], $params));
    }

    #[Route("/edit/{id}", name: "edit", requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);
        $posting = $this->postingRepository->find($id);

        if (!$posting->canEdit($this->getAdmin())) {
            throw $this->createAccessDeniedException("You are not allowed to edit this posting");
        }

        return $this->handlePostingForm($posting, $request, [
            'form' => $this->createForm(PostingType::class, $this->postingRepository->find($id))->createView(),
        ]);
    }

    #[Route("/", name: "index")]
    public function users(Request $request): Response
    {
        $pagination = $this->pagination->handleRequest($request);
        return $this->render('pages/admin/posting/index.html.twig', $pagination);
    }

    #[Route("/_search", name: "search")]
    public function search(Request $request): Response
    {
        $pagination = $this->pagination->handleRequest($request);
        $qb = $this->postingRepository->getDisplayedPostingsQb($data ?? []);
        $totalItems = (clone $qb)->select('COUNT(p)')->getQuery()->getSingleScalarResult();

        $qb = $this->pagination->attachPagination($qb, $pagination);
        $postings = $qb->getQuery()->getResult();

        return $this->render('pages/admin/posting/postings.html.twig', [
            'postings' => $postings,
            ...$pagination,
            'total' => $totalItems,
        ]);
    }
}
