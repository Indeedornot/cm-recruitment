<?php

namespace App\Security\EventListener;

use App\Security\Entity\Admin;
use App\Security\Event\UserEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;

#[AsEventListener(event: UserEvent::PRE_USER_CREATED, method: 'onPreUserCreated')]
class UserEventListener
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    public function onPreUserCreated(UserEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof Admin) {
            $email = (new TemplatedEmail())
                ->from('gravitybartek@gmail.com')
                ->to($user->getEmail())
                ->subject('New admin account created')
                ->htmlTemplate('mails/security/create-admin-account.html.twig')
                ->context([
                    'user' => $user
                ]);
            $this->mailer->send($email);
        } else {
            $email = (new TemplatedEmail())
                ->from('gravitybartek@gmail.com')
                ->to($user->getEmail())
                ->subject('New account created')
                ->htmlTemplate('mails/security/create-client-account.html.twig')
                ->context([
                    'user' => $user
                ]);
        }
    }
}
