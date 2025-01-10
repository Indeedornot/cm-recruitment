<?php

namespace App\Services\Posting;

use App\Security\Services\ExtendedSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PostingService
{
    public function __construct(private readonly ExtendedSecurity $security, private readonly RequestStack $request)
    {
    }

    public function setAcceptedPrivacyPolicy(int $id, bool $val = true): self
    {
        $this->request->getSession()->set('PRIVACY_POLICY_ACCEPTED', $val);
        return $this;
    }

    public function isAcceptedPrivacyPolicy(int $id): bool
    {
        return $this->security->isLoggedIn() || !empty($this->request->getSession()->get('PRIVACY_POLICY_ACCEPTED'));
    }
}
