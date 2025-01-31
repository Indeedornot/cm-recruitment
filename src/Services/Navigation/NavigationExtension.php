<?php

namespace App\Services\Navigation;

use App\Security\Entity\Admin;
use App\Security\Entity\UserRoles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    public function __construct(
        private Security $security,
        private RouterInterface $router,
        private RequestStack $stack,
        private NavRouteFactory $routes
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getNavLinks', [$this, 'getNavLinks']),
            new TwigFunction('isCurrentRoute', [$this, 'isCurrentRoute'])
        ];
    }

    /** @return array<string, NavRoute> */
    public function getNavLinks(): array
    {
        $isLoggedIn = $this->security->getUser() !== null;
        $routes = [
            'list' => $this->routes->new(
                key: $this->security->isGranted(UserRoles::ADMIN->value) ? 'app_admin_index' : 'app_user_index',
                label: 'Strona Główna',
            ),
            'login' => $this->routes->new(
                key: 'app_login',
                label: 'Zaloguj się',
                enabled: !$isLoggedIn && !$this->isCurrentRoute('app_login')
            ),
            'logout' => $this->routes->new(
                key: 'app_logout',
                label: 'Wyloguj się',
                enabled: $isLoggedIn && !$this->isCurrentRoute('app_logout')
            )
        ];

        return $routes;
    }

    public function isCurrentRoute(string $routeName): bool
    {
        return $this->routes->isCurrentRoute($routeName);
    }

    public function getPostLoginRoute(): string
    {
        return $this->getIndexRoute() ?? $this->router->generate('app_user_index');
    }

    public function getIndexRoute(): ?string
    {
        $user = $this->security->getUser();
        if ($user instanceof Admin) {
            return $this->router->generate('app_admin_index');
        }

        return null;
    }
}
