<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\LogoutEvent;
use Psr\Log\LoggerInterface;

class LogoutListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $authLogger,)
    {
        $this->logger = $authLogger;
    }

    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $logoutEvent): void
    {
        $this->logger->info(sprintf('[USER LOGOUT] User %s has logged out.', $logoutEvent->getToken()->getUser()->getUserIdentifier()));
    }
}