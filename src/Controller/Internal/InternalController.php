<?php

namespace App\Controller\Internal;

use App\Controller\Base\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

#[Route('/internal', name: 'app_internal_')]
class InternalController extends BaseController
{
    public function __construct(
        private Environment $twig,
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/translations.js', name: 'translations_js')]
    public function translationsJs(): Response
    {
        $translations = $this->twig->render('internal/translations.js.html.twig', [
            'translations' => $this->translator->getCatalogue()->all('messages')
        ]);
        $response = new Response($translations);
        $response->headers->set('Content-Type', 'application/javascript');
        return $response;
    }
}
