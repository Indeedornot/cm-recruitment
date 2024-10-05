<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
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
        try {
            $posting = new Posting();
            $form = $this->createForm(PostingType::class, $posting);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($posting);
                $this->em->flush();

                return $this->render('pages/admin/posting/create.html.twig', [
                    'form' => $this->createForm(PostingType::class)->createView()
                ]);
            }

            return $this->render('pages/admin/posting/create.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $e) {
//            TODO: Advanced error handling for forms? Separate message?
            return $this->render('errors/index.html.twig', [
                'error' => $e
            ]);
        }
    }
}
