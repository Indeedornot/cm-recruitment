<?php

namespace App\Services\Navigation;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class NavRouteFactory
{
    public function __construct(
        private RouterInterface $router,
        private RequestStack    $stack,
    )
    {
    }

    public function new(
        string $key,
        string $label,
        bool   $enabled = true
    )
    {
        return new NavRoute(
            key: $key,
            label: $label,
            enabled: $enabled,
            href: $this->router->generate($key),
            isCurrent: $this->isCurrentRoute($key)
        );
    }

    public function isCurrentRoute(string $routeName): bool
    {
        $currentRequest = $this->stack->getCurrentRequest();
        return $currentRequest && $currentRequest->attributes->get('_route') === $routeName;
    }
}
