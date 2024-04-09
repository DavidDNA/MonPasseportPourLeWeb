<?php

namespace App\Teacher;

use App\Entity\Teacher;
use App\Utils\RandomPasswordGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class TeacherService implements TeacherServiceInterface {

    public function __construct(private readonly TeacherMailSender                $mailSendr,
                                private readonly UserPasswordHasherInterface      $userPasswordHasher,
                                private readonly EntityManagerInterface           $entityManager,
                                private readonly TranslatorInterface              $translator,
                                private readonly RandomPasswordGeneratorInterface $passwordGenerator,
                                private readonly string                           $mpwMail,
                                private readonly string                           $mpwName) {
    }

    /**
     * @inheritDoc
     * @param Teacher $teacher
     * @param string $plainPassword
     * @return Teacher
     * @throws TransportExceptionInterface
     */
    public function createAccount(Teacher $teacher): Teacher {
        // we hash the password
        $teacher->setPassword($this->userPasswordHasher->hashPassword($teacher, $teacher->getPassword()));

        // we persist the entity
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        // we send the confirmation email containing the link
        $this->mailSendr->sendEmailConfirmation('teacher_verify_email', $teacher,
            (new TemplatedEmail())
                ->from(new Address($this->mpwMail, $this->mpwName))
                ->to($teacher->getEmail())
                ->subject($this->translator->trans('teacher.create.email.title'))
                ->htmlTemplate('emails/teacher_confirmation.html.twig')
        );

        return $teacher;
    }

    /**
     * @inheritDoc
     * @param string $teacherId
     * @param string $validationURI
     * @return bool
     */
    public function verifyEmail(string $teacherId, string $validationURI): bool {
        $teacherRepository = $this->entityManager->getRepository(Teacher::class);
        $user = $teacherRepository->find($teacherId);

        if ($user === null) {
            return false;
        }

        try {
            $this->mailSendr->handleEmailConfirmation($validationURI, $user);
        } catch (VerifyEmailExceptionInterface) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     * @param Teacher $teacher
     * @return void
     */
    public function board(Teacher $teacher): void {
        $teacher->setOnBoard(true);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     * @param Teacher $teacher
     * @param string $newPassword
     * @return void
     */
    public function changePassword(Teacher $teacher, string $newPassword): void {
        $teacher->setPassword($this->userPasswordHasher->hashPassword($teacher, $newPassword));
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     * @param string $email
     * @return void
     * @throws TransportExceptionInterface
     */
    public function recoverPassword(string $email): void {
        /** @var Teacher $teacher */
        $teacher = $this->entityManager->getRepository(Teacher::class)->findOneBy(["email" => $email]);
        if ($teacher !== null) {
            $newPassword = $this->getPassword();
            $teacher->setPassword($this->userPasswordHasher->hashPassword($teacher, $newPassword));

            $this->mailSendr->sendEmailRecovery($teacher, $newPassword,
                (new TemplatedEmail())
                    ->from(new Address($this->mpwMail, $this->mpwName))
                    ->to($teacher->getEmail())
                    ->subject($this->translator->trans('teacher.recovery.email.title'))
                    ->htmlTemplate('emails/teacher_recovery.html.twig')
            );

            $this->entityManager->flush();
        }
    }

    /**
     * @inheritDoc
     * @param Teacher $teacher
     * @param string $message
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendFeedback(Teacher $teacher, string $name, string $message): void {
        $this->mailSendr->sendFeedbackEmail($teacher, $name, $message,
            (new TemplatedEmail())
                ->from(new Address($this->mpwMail, $this->mpwName))
                ->to(new Address($this->mpwMail, $this->mpwName))
                ->replyTo($teacher->getEmail())
                ->subject($this->translator->trans('teacher.feedback.email.title'))
                ->htmlTemplate('emails/teacher_feedback.html.twig')
        );
    }

    /**
     * Gets a randomly generated password.
     *
     * @return string
     */
    private function getPassword(): string {
        return $this->passwordGenerator->getRandomPassword(6);
    }
}
