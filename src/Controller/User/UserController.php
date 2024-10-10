<?php

namespace App\Controller\User;

use App\Controller\Base\BaseController;
use App\Entity\ClientApplication;
use App\Form\ClientApplicationType;
use App\Repository\PostingRepository;
use App\Security\Entity\UserRoles;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'app_user_')]
class UserController extends BaseController
{
    function __construct(private PostingRepository $postingRepository, private EntityManagerInterface $em)
    {
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

        $clientApplication = (new ClientApplication())->setClient($this->getClient())->setPosting($posting);
        $form = $this->createForm(ClientApplicationType::class, $clientApplication);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->em->persist($clientApplication);
            $this->em->flush();
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('pages/user/application/index.html.twig', [
            'posting' => $posting,
            'form' => $form->createView(),
        ]);
    }
}
