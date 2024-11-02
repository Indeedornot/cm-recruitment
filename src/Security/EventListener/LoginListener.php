<?php

namespace App\Security\EventListener;

use App\Security\Event\UserEvent;
use App\Security\Services\ExtendedSecurity;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Throwable;

#[AsEventListener(event: KernelEvents::REQUEST, method: 'onKernelRequest')]
class LoginListener
{
    public const string RESET_PASSWORD_ROUTE = 'app_reset_password';

    function __construct(
        private readonly ExtendedSecurity $security,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // only deal with the main request, disregard subrequests
        if (!$event->isMainRequest()) {
            return;
        }

        // if we are visiting the password change route, no need to redirect
        // otherwise we'd create an infinite redirection loop
        if ($event->getRequest()->get('_route') === self::RESET_PASSWORD_ROUTE) {
            return;
        }

        if (!$this->security->isLoggedIn()) {
            return;
        }

        // if it's not their first login, and they do not need to change their password, move on
        $user = $this->security->getUser();
        if (!$user->isPasswordChangeRequired()) {
            return;
        }

        // if we get here, it means we need to redirect them to the password change view.
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate(self::RESET_PASSWORD_ROUTE)));
    }
}
