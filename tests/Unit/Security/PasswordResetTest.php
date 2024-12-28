<?php

use App\Security\EventListener\LoginListener;
use App\Security\Services\ExtendedSecurity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Security\Entity\User;

uses(KernelTestCase::class);

describe('Password Reset Listener', function () {
    function createListener($security = null, $urlGenerator = null): LoginListener
    {
        $security ??= mock(ExtendedSecurity::class)->makePartial();
        $urlGenerator ??= mock(UrlGeneratorInterface::class)->makePartial();

        return new LoginListener($security, $urlGenerator);
    }

    function createRequestEvent($routeName = null): RequestEvent
    {
        $request = Request::create('/');
        if ($routeName) {
            $request->attributes->set('_route', $routeName);
        }

        $event = mock(RequestEvent::class)->makePartial()
            ->allows([
                'isMainRequest' => true,
                'getRequest' => $request
            ]);
        return $event;
    }

    test('redirects to password reset when user needs to change password', function () {
        $user = mock(User::class)->makePartial()
            ->allows([
                'isPasswordChangeRequired' => true
            ]);

        $security = mock(ExtendedSecurity::class)->makePartial()
            ->allows([
                'isLoggedIn' => true,
                'getUser' => $user
            ]);

        $urlGenerator = mock(UrlGeneratorInterface::class)->makePartial();
        $urlGenerator->allows('generate')
            ->with(LoginListener::RESET_PASSWORD_ROUTE)
            ->andReturn('test-url');

        $listener = createListener($security, $urlGenerator);
        $event = createRequestEvent();
        $listener->onKernelRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();
        expect($response)
            ->toBeInstanceOf(RedirectResponse::class)
            ->and($response->getTargetUrl())
            ->toBe('test-url');
    });

    test('does not redirect when user does not need to change password', function () {
        // Arrange
        $user = mock(User::class)->makePartial()
            ->allows([
                'isPasswordChangeRequired' => false
            ]);

        $security = mock(ExtendedSecurity::class)->makePartial()
            ->allows([
                'isLoggedIn' => true,
                'getUser' => $user
            ]);

        $listener = createListener($security);
        $event = createRequestEvent();

        $listener->onKernelRequest($event);
        expect($event)->not->toHaveProperty('response');
    });

    test('does not redirect on reset password route', function () {
        $listener = createListener();
        $event = createRequestEvent(LoginListener::RESET_PASSWORD_ROUTE);

        $listener->onKernelRequest($event);
        expect($event)->not->toHaveProperty('response');
    });

    test('validates reset password route exists', function () {
        $urlGenerator = $this->getContainer()->get(UrlGeneratorInterface::class);

        $user = mock(User::class)->makePartial()
            ->allows(['isPasswordChangeRequired' => true]);

        $security = mock(ExtendedSecurity::class)->makePartial()
            ->allows([
                'isLoggedIn' => true,
                'getUser' => $user
            ]);

        $listener = createListener($security, $urlGenerator);
        $event = createRequestEvent();
        expect(fn() => $listener->onKernelRequest($event))->not->toThrow(Exception::class);
    });
});
