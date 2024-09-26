<?php

namespace App\Services\Assets;

use App\Entity\UserRoles;
use App\Services\Navigation\NavRoute;
use App\Services\Navigation\NavRouteFactory;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetsExtension extends AbstractExtension
{
    public function __construct()
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('assets_scripts', $this->getScripts(...)),
            new TwigFunction('assets_styles', $this->getStyles(...)),
        ];
    }

    public function getScripts(): ScriptCollection
    {
        return ScriptCollection::getInstance();
    }

    public function getStyles(): StyleCollection
    {
        return StyleCollection::getInstance();
    }
}
