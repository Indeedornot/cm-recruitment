<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Security\Entity\UserRoles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(UserRoles::CLIENT->value)]
#[Route('/user', name: 'app_user_')]
class UserController extends BaseController
{
    #[Route("/", name: "index")]
    public function user(Request $request): Response
    {
        return $this->render('pages/user/index.html.twig');
    }
}
