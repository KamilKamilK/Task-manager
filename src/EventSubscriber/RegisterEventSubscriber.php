<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Security\EmailVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegisterEventSubscriber implements EventSubscriberInterface
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public function onRegistrationSuccess(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onRegistrationSuccess'
        ];
    }
}