<?php

namespace App\Controller\Base;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Twig\Environment;

#[AsEventListener]
class ErrorHandler
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function __invoke(ExceptionEvent $e): void
    {
        $error_handler = $e->getRequest()->getSession()->get(
            'error_handler',
            ['handler' => ErrorHandlerType::DEFAULT->value, 'options' => []]
        );

        $handler = match ($error_handler['handler']) {
            ErrorHandlerType::FORM->value => 'form_error',
            default => 'index',
        };

        $e->setResponse(
            new Response(
                $this->renderErrorPage(
                    $handler,
                    array_merge($error_handler['options'], ['error' => $e->getThrowable()])
                )
            )
        );
    }

    private function renderErrorPage($handler, $options)
    {
        try {
            return $this->twig->render('errors/' . $handler . '.html.twig', $options);
        } catch (\Exception) {
            return $this->twig->render('errors/index.html.twig', $options);
        }
    }
}
