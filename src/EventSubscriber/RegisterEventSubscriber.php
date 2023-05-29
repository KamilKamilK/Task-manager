<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegisterEventSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private EmailVerifier $emailVerifier;
    private VerifyEmailHelperInterface $verifyEmailHelper;

    public function __construct(MailerInterface $mailer, EmailVerifier $emailVerifier, VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->mailer = $mailer;
        $this->emailVerifier = $emailVerifier;
        $this->verifyEmailHelper = $verifyEmailHelper;
    }

    public function onRegistrationSuccess(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();
//
//                $email = (new TemplatedEmail())
//            ->from(new Address('kamil@gmail.com', 'Task Manager By Kamil'))
//            ->to($user->getEmail())
//            ->subject('Email confirmation link to Task Manager application')
//            ->text('Please Confirm your Email by clicking on: ' .$signatureComponents->getSignedUrl())
//            ->htmlTemplate('registration/confirmation_email.html.twig');

        $this->emailVerifier->sendEmailConfirmation('app_verify_email',$user);


//        $signatureComponents = $this->verifyEmailHelper->generateSignature(
//            'app_verify_email',
//            (string)$user->getId(),
//            $user->getEmail(),
//            ['id' => $user->getId()]
//        );
//
//        $email = (new TemplatedEmail())
//            ->from(new Address('kamil@gmail.com', 'Task Manager By Kamil'))
//            ->to($user->getEmail())
//            ->subject('Email confirmation link to Task Manager application')
//            ->text('Please Confirm your Email by clicking on: ' .$signatureComponents->getSignedUrl())
//            ->htmlTemplate('registration/confirmation_email.html.twig');

//        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//            $email
//        );

//        $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onRegistrationSuccess'
        ];
    }
}