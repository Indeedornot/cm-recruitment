<?php

namespace App\Security\Services;

use App\Security\Entity\UserRoles;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SecurityExtension extends AbstractExtension
{
    public function __construct(private ExtendedSecurity $security)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_admin', [$this, 'isAdmin']),
            new TwigFunction('is_client', [$this, 'isClient']),
            new TwigFunction('is_loggedin', [$this, 'isLoggedIn']),
            new TwigFunction('is_super_admin', [$this, 'isSuperAdmin']),
            new TwigFunction('encode_value', [$this, 'encodeValue']),
        ];
    }

    public function isAdmin(): bool
    {
        return $this->security->isGranted(UserRoles::ADMIN->value);
    }

    public function isSuperAdmin(): bool
    {
        return $this->security->isGranted(UserRoles::SUPER_ADMIN->value);
    }

    public function isClient(): bool
    {
        return $this->security->isGranted(UserRoles::CLIENT->value);
    }

    public function isLoggedIn(): bool
    {
        return !empty($this->security->getUser());
    }

    public function encodeValue(string|int $value): string
    {
        return $this->security->encodeValue((string)$value);
    }
}
