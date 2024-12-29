<?php

namespace App\Security\Services;

use App\Security\Entity\User;
use App\Security\Entity\UserRoles;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\SecurityBundle\Security\FirewallConfig;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Security
 */
class ExtendedSecurity
{
    public function __construct(
        private readonly Security $security,
        private readonly ParameterBagInterface $container,
    ) {
    }

    private function getSecret(): string
    {
        return $this->container->get('kernel.secret');
    }

    public function encodeValue(string $value): string
    {
        $secret = $this->getSecret();
        return base64_encode($value . $secret);
    }

    public function decodeValue(string $value): string
    {
        $decoded = base64_decode($value);
        $secret = $this->getSecret();
        return str_replace($secret, '', $decoded);
    }

    public function isSuperAdmin(): bool
    {
        return $this->isGranted(UserRoles::SUPER_ADMIN->value);
    }

    public function isAdmin(): bool
    {
        return $this->isGranted(UserRoles::ADMIN->value);
    }

    public function isGranted(mixed $attributes, mixed $subject = null): bool
    {
        return $this->security->isGranted($attributes, $subject);
    }

    public function isClient(): bool
    {
        return $this->isGranted(UserRoles::CLIENT->value);
    }

    public function isAcceptedPrivacyPolicy(): bool
    {
        return $this->isLoggedIn() || !empty($_SESSION['PRIVACY_POLICY_ACCEPTED']);
    }

    public function isLoggedIn(): bool
    {
        return !empty($this->getUser());
    }

    public function getUser(): ?User
    {
        return $this->security->getUser();
    }

    public function setAcceptedPrivacyPolicy(bool $val): self
    {
        $_SESSION['PRIVACY_POLICY_ACCEPTED'] = $val;
        return $this;
    }

    public function login(
        UserInterface $user,
        ?string $authenticatorName = null,
        ?string $firewallName = null,
        array $badges = []
    ): ?Response {
        return $this->security->login($user, $authenticatorName, $firewallName, $badges);
    }

    public function logout(bool $validateCsrfToken = true): ?Response
    {
        return $this->security->logout($validateCsrfToken);
    }

    public function getToken(): ?TokenInterface
    {
        return $this->security->getToken();
    }

    public function getFirewallConfig(Request $request): ?FirewallConfig
    {
        return $this->security->getFirewallConfig($request);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->security->$name(...$arguments);
    }
}
