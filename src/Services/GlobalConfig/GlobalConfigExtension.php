<?php

namespace App\Services\GlobalConfig;

use App\Repository\GlobalConfigRepository;
use App\Security\Entity\Admin;
use App\Security\Entity\UserRoles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GlobalConfigExtension extends AbstractExtension
{
    public function __construct(
        private readonly GlobalConfigRepository $globalConfigRepository
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getGlobalConfig', [$this, 'getGlobalConfig'])
        ];
    }

    public function getGlobalConfig(string $key, mixed $default = null): mixed
    {
        return $this->globalConfigRepository->getValue($key, $default);
    }
}
