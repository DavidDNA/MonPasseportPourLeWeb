<?php

namespace App\Teacher;

use App\Entity\Teacher;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface TeacherMailSenderInterface {

    /**
     * Sends a registration confirmation to the given user by email.
     *
     * @param string $verifyEmailRouteName
     * @param Teacher $teacher
     * @param TemplatedEmail $email
     * @return void
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, Teacher $teacher, TemplatedEmail $email): void;

    /**
     * Verifies the user email address and activates their account.
     *
     * @param string $validationURI
     * @param Teacher $teacher
     * @return void
     */
    public function handleEmailConfirmation(string $validationURI, Teacher $teacher): void;

    /**
     * Sends a new password by email to the given user.
     *
     * @param Teacher $teacher
     * @param string $plainPassword
     * @param TemplatedEmail $email
     * @return void
     */
    public function sendEmailRecovery(Teacher $teacher, string $plainPassword, TemplatedEmail $email): void;

    /**
     * Sends the feedback e-mail.
     *
     * @param Teacher $teacher
     * @param string $name
     * @param string $message
     * @param TemplatedEmail $email
     * @return void
     */
    public function sendFeedbackEmail(Teacher $teacher, string $name, string $message, TemplatedEmail $email): void;
}
