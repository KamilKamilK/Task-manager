<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\UserRegisteredEvent;
use Psr\Log\LoggerInterface;

class UserRegisteredListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $authLogger,)
    {
        $this->logger = $authLogger;
    }

    public function onRegistrationSuccess(UserRegisteredEvent $event): bool
    {
        $this->logger->info(sprintf('[NEW USER] New user was registered! User email: %s', $event->getUser()->getEmail()));
        return true;
    }
}