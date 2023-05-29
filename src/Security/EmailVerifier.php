<?php
declare(strict_types=1);

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface            $mailer,
        private EntityManagerInterface     $entityManager
    )
    {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user): void
    {
        $signatureComponents = $this->createSignatureComponents($verifyEmailRouteName, $user);

        $email = (new TemplatedEmail())
            ->from(new Address('kamil@gmail.com', 'Task Manager By Kamil'))
            ->to($user->getEmail())
            ->subject('Email confirmation link to Task Manager application')
            ->text('Please Confirm your Email by clicking on: ' . $signatureComponents->getSignedUrl());
//            ->htmlTemplate('registration/confirmation_email.html.twig');

//        $context = $email->getContext();
//        $context['signedUrl'] = $signatureComponents->getSignedUrl();
//        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
//        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();


//        $email->context($context);
//        dd($email);
//        dd( $this->mailer->send($email));
        $address = new Address('kamil@gmail.com', 'Task Manager By Kamil');
        $recipientAddress = new Address($user->getEmail(), 'Recipient receives email By Kamil');

        $envelope = new Envelope($address, [$recipientAddress]);
        $envelope->getSender($address);
        $envelope->setRecipients([$recipientAddress]);
//        $address = new Address('kamil@gmail.com', 'Task Manager By Kamil');
        $message = new RawMessage('Kamil wysyła wiadomość');

//        $envelope = new Envelope($address, $email);
//        dd( $this->mailer->send($message, $envelope));
        $this->mailer->send($message, $envelope);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation(
            $request->getUri(),
            (string)$user->getId(),
            $user->getEmail()
        );
        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function createSignatureComponents($verifyEmailRouteName, $user): VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string)$user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );
    }
}
