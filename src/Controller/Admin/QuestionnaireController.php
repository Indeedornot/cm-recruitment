<?php

namespace App\Controller\Admin;

use App\Controller\Base\BaseController;
use App\Security\Entity\UserRoles;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(UserRoles::ADMIN->value)]
#[Route("/admin/questionnaire", name: "app_admin_questionnaire_")]
class QuestionnaireController extends BaseController
{
    #[Route("/", name: "index")]
    public function index(): Response
    {
//        return $this->render('pages/admin/questionnaire/index.html.twig', [
//            'questionnaires' => $this->questionnaireRepository->findAll(),
//        ]);
        return new Response();
    }
}
