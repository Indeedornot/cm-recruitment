<?php

namespace App\Services\Navigation;

use App\Security\Entity\Admin;
use App\Security\Entity\User;
use App\Security\Services\ExtendedSecurity;
use LogicException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

#[AsEventListener(event: KernelEvents::REQUEST, method: 'onKernelRequest', priority: 1)]
class NavigationListener
{

    function __construct(
        private readonly ExtendedSecurity $security,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly AccessDecisionManagerInterface $router
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

        //check if path starts with `/user` and user is an admin
        if (str_starts_with($event->getRequest()->getPathInfo(), '/user') && $user instanceof Admin) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_index_index')));
        }
    }
}
