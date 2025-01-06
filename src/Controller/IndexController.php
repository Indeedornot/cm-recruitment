<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Services\Navigation\NavigationExtension;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_index_')]
class IndexController extends BaseController
{
    public function __construct(
        private readonly NavigationExtension $navigationExtension,
        private readonly Packages $assetExtension
    ) {
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

    #[Route(path: '/regulamin', name: 'regulamin')]
    public function regulamin(): Response
    {
        return $this->redirect($this->assetExtension->getUrl('build/images/regulamin_rekrutacji.pdf'));
    }
}
