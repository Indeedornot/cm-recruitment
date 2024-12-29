<?php

namespace App\Security\Services;

use App\Entity\EmailReport;
use App\Security\Entity\Admin;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\Event\SentMessageEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;

#[AsEventListener(event: MessageEvent::class, method: 'onMessage', priority: -1)]
#[AsEventListener(event: SentMessageEvent::class, method: 'onMessageSent', priority: -1)]
class UserEmailService
{
//                TODO: CHANGE ME
    private string $from = 'cm_dev@cm.com';
    private ?User $user = null;

    public function __construct(
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
        private EntityManagerInterface $em,
        private ExtendedSecurity $extendedSecurity,
        private Environment $twig
    ) {
    }

    public function onMessage(MessageEvent $event): void
    {
        /** @var Email $email */
        $email = $event->getMessage();

        $emailReport = new EmailReport();
        $body = $email->getHtmlBody() ?? $email->getTextBody() ?? $email->getBody()->bodyToString() ?? '';
        $userIds = $email->getHeaders()->has('X-User-Ids') ? json_decode($email->getHeaders()->getHeaderBody('X-User-Ids')) : [];
        $emailId = $email->generateMessageId();

        $emailReport->setSubject($email->getSubject())
            ->setRecipients($email->getTo())
            ->setRecipientIds($userIds)
            ->setBody($body)
            ->setCreatedBy($this->extendedSecurity->getUser());
        $this->em->persist($emailReport);
        $this->em->flush();

        $email->getHeaders()->addTextHeader('X-Email-Report-Id', $emailReport->getId());
    }

    public function onMessageSent(SentMessageEvent $event): void
    {
        $email = $event->getMessage()->getOriginalMessage();
        $emailReportId = $email->getHeaders()->get('X-Email-Report-Id')->getBodyAsString();
        $emailReport = $this->em->getRepository(EmailReport::class)->findOneBy(['id' => $emailReportId]);
        $emailReport->setSentAt(new DateTimeImmutable());
        $this->em->flush();
    }

    /**
     * @throws LoaderError
     */
    private function getTemplateFile(string $template): string
    {
        if ($this->twig->getLoader()->exists($template)) {
            return $template;
        } else {
            $locale = $this->translator->getLocale();
            $template = str_replace('.html.twig', ".$locale.html.twig", $template);
            if ($this->twig->getLoader()->exists($template)) {
                return $template;
            } else {
                throw new LoaderError('Email template not found');
            }
        }
    }

    public function sendToUserAccountCreatedMail(User $user): void
    {
        $this->user = $user;
        if ($user instanceof Admin) {
            $template = 'mails/security/create-admin-account.html.twig';
            $subject = $this->translator->trans('emails.security.create-admin-account.subject');
        } else {
            $template = 'mails/security/create-client-account.html.twig';
            $subject = $this->translator->trans('emails.security.create-client-account.subject');
        }

        $email = (new TemplatedEmail())
            ->from($this->from)
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate($this->getTemplateFile($template))
            ->context([
                'user' => $user
            ]);

        $this->sendEmail($email);
    }

//    public function sendToUserPasswordChangedMail(User $user): void
//    {
//        $this->user = $user;
//        $email = (new TemplatedEmail())
//            ->from($this->from)
//            ->to($user->getEmail())
//            ->subject('Password changed')
//            ->htmlTemplate($this->getTemplateFile('mails/security/password-changed.html.twig'))
//            ->context([
//                'user' => $user
//            ]);
//        $this->sendEmail($email);
//    }

    public function sendToUserPasswordResetMail(User $user): void
    {
        $this->user = $user;
        $email = (new TemplatedEmail())
            ->from($this->from)
            ->to($user->getEmail())
            ->subject($this->translator->trans('emails.security.password_reset.subject'))
            ->htmlTemplate($this->getTemplateFile('mails/security/password-reset.html.twig'))
            ->context([
                'user' => $user
            ]);
        $this->sendEmail($email);
    }

    private function sendEmail(Email $email): void
    {
        try {
            if (!empty($this->user)) {
                $email->getHeaders()
                    ->addTextHeader('X-User-Ids', json_encode([$this->user->getId()]));
                $this->user = null;
            }

            $this->mailer->send($email);
        } catch (\Exception $e) {
        }
    }
}
