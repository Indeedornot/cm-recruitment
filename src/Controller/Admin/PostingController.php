<?php

namespace App\Controller\Admin;

use App\Controller\Base\BaseController;
use App\Controller\Base\ErrorHandlerType;
use App\Entity\Posting;
use App\Form\PostingType;
use App\Repository\PostingRepository;
use App\Security\Entity\UserRoles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(UserRoles::ADMIN->value)]
#[Route("/admin/posting", name: "app_admin_posting_")]
class PostingController extends BaseController
{
    function __construct(private PostingRepository $postingRepository, private EntityManagerInterface $em)
    {
    }

    #[Route("/", name: "index")]
    public function index(): Response
    {
        return $this->render('pages/admin/posting/index.html.twig', [
            'postings' => $this->postingRepository->findAll(),
        ]);
    }

    #[Route("/create", name: "create")]
    public function create(Request $request): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);

        $posting = new Posting();
        $posting->setCreatedBy($this->getAdmin());
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
        }

        return $this->render('pages/admin/posting/manage.html.twig', array_merge([
            'form' => $form->createView(),
        ], $params));
    }

    #[Route("/edit", name: "edit")]
    public function edit(Request $request): Response
    {
        $this->setErrorHandler(ErrorHandlerType::FORM);

        $id = $request->query->get('id');
        $posting = $this->postingRepository->find($id);

        if ($this->getAdmin()->getId() !== $posting->getAssignedTo()->getId() &&
            !$this->isGranted(UserRoles::SUPER_ADMIN->value)
        ) {
            $this->createAccessDeniedException("You are not allowed to edit this posting");
        }


        return $this->handlePostingForm($posting, $request, [
            'form' => $this->createForm(PostingType::class, $this->postingRepository->find($id))->createView(),
        ]);
    }
}
