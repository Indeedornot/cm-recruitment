<?php

namespace App\Controller;

use App\Services\Navigation\NavigationExtension;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_index_')]
class IndexController extends BaseController
{
    public function __construct(private readonly NavigationExtension $navigationExtension)
    {
    }

    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        $index = $this->navigationExtension->getPostLoginRoute();
        if (!empty($index)) {
            return $this->redirect($index);
        }

        return $this->render('pages/user/index.html.twig');
    }
}
