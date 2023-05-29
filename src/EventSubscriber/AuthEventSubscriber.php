<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class AuthEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $authLogger)
    {

        $this->logger = $authLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onAuthenticationSuccess',
            LoginFailureEvent::class => 'onAuthenticationFailure',
            CheckPassportEvent::class => 'onCheckPassportEvent'
        ];
    }

    public function onCheckPassportEvent(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();

        if (!$passport instanceof UserPassportInterface) {
            throw new \Exception('Unexpected passport type');
        }

        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

        if (!$user->isVerified()) {
            $this->logger->info(sprintf('[USER LOGIN VERIFICATION FAILED] User %s has not verified email before logged in.', $user->getEmail()));

            throw new CustomUserMessageAuthenticationException(
                'Please verify your account before logging in'
            );
        }
    }

    public function onAuthenticationSuccess(InteractiveLoginEvent $event): void
    {
        $this->logger->info(sprintf('[USER LOGIN] User %s has logged in.', $event->getRequest()->request->get('email')));
    }

    public function onAuthenticationFailure(LoginFailureEvent $event): void
    {
        $this->logger->info(sprintf('[USER LOGIN FAILED] User %s has failed to logged in. Exception message: %s',
            $event->getRequest()->request->get('email'),
            $event->getException()->getMessage()
        ));

    }
}