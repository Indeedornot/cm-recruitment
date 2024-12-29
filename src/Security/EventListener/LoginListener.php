<?php

namespace App\Security\EventListener;

use App\Security\Entity\User;
use App\Security\Services\ExtendedSecurity;
use LogicException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

        if (!$this->security->isLoggedIn()) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new LogicException('User must be an instance of User');
        }

        if ($user->isDisabled()) {
            $this->security->logout(false);
            $event->getRequest()->getSession()->getFlashBag()->add('error', 'security.disabled_account');
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_login')));
            return;
        }

        // if it's not their first login, and they do not need to change their password, move on
        if (!$user->isPasswordChangeRequired()) {
            return;
        }

        // if we are visiting the password change route, no need to redirect
        // otherwise we'd create an infinite redirection loop
        if ($event->getRequest()->get('_route') === self::RESET_PASSWORD_ROUTE) {
            return;
        }

        // if we get here, it means we need to redirect them to the password change view.
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate(self::RESET_PASSWORD_ROUTE)));
    }
}
