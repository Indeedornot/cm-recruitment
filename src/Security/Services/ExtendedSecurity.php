<?php

namespace App\Security\Services;

use App\Security\Entity\User;
use App\Security\Entity\UserRoles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\SecurityBundle\Security\FirewallConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Security\Core\Exception\LogoutException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\ParameterBagUtils;
use Symfony\Contracts\Service\ServiceProviderInterface;

/**
 * @extends Security
 */
class ExtendedSecurity
{
    public function __construct(
        private readonly Security $security
    ) {
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
