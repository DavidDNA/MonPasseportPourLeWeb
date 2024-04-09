<?php

namespace App\Teacher;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class TeacherMailSender implements TeacherMailSenderInterface {

    public function __construct(private readonly VerifyEmailHelperInterface $verifyEmailHelper,
                                private readonly MailerInterface            $mailer,
                                private readonly EntityManagerInterface     $entityManager) {
    }

    /**
     * @inheritDoc
     * @param string $verifyEmailRouteName
     * @param UserInterface $teacher
     * @param TemplatedEmail $email
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, Teacher $teacher, TemplatedEmail $email): void {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $teacher->getId(),
            $teacher->getEmail(),
            ['id' => $teacher->getId()]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @inheritDoc
     * @param string $validationURI
     * @param Teacher $teacher
     * @return void
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(string $validationURI, Teacher $teacher): void {
        $this->verifyEmailHelper->validateEmailConfirmation($validationURI, $teacher->getId(), $teacher->getEmail());

        $teacher->setIsVerified(true);

        $this->entityManager->persist($teacher);
        $this->entityManager->flush();
    }

    /**
     * @inhericDoc
     * @param Teacher $teacher
     * @param string $plainPassword
     * @param TemplatedEmail $email
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendEmailRecovery(Teacher $teacher, string $plainPassword, TemplatedEmail $email): void {
        $context = $email->getContext();
        $context['password'] = $plainPassword;
        $email->context($context);
        $this->mailer->send($email);
    }

    /**
     * @inhericDoc
     * @param Teacher $teacher
     * @param string $name
     * @param string $message
     * @param TemplatedEmail $email
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendFeedbackEmail(Teacher $teacher, string $name, string $message, TemplatedEmail $email): void {
        $context = $email->getContext();
        $context['name'] = $name;
        // cannot use email tpl variable as it's a reserved one
        $context['mail'] = $teacher->getEmail();
        $context['message'] = $message;
        $email->context($context);
        $this->mailer->send($email);
    }
}
