<?php

namespace App\Teacher;

use App\Entity\Teacher;

interface TeacherServiceInterface {

    /**
     * Creates a new teacher account. This method will persist the given
     * Teacher entity and securely hash a random password generated (passed
     * as a second parameter).
     *
     * When an account is created, it won't be active until the user verifies
     * their address by clicking on a link provided in a confirmation email.
     *
     * @param Teacher $teacher Entity to persist
     * @return Teacher The persisted entity
     */
    public function createAccount(Teacher $teacher): Teacher;

    /**
     * Verifies an email after a new account has been registered. Returns
     * true if the account has been successfully verified and false if
     * there was an error (we don't provide details atm).
     *
     * @param string $teacherId Teacher entity id
     * @param string $validationURI URI validation containing parameters
     * @return bool Verification result
     */
    public function verifyEmail(string $teacherId, string $validationURI): bool;

    /**
     * Sets the teacher on board - meaning we consider they have received
     * all the essential information to start using the app.
     *
     * @param Teacher $teacher
     * @return void
     */
    public function board(Teacher $teacher): void;

    /**
     * Sets a new password to the teacher.
     *
     * @param Teacher $teacher
     * @param mixed $newPassword
     */
    public function changePassword(Teacher $teacher, string $newPassword): void;

    /**
     * Initiates a password recovery sequence.
     * @param string $email
     */
    public function recoverPassword(string $email): void;

    /**+
     * Sends a feedback.
     *
     * @param Teacher $teacher
     * @param string $name
     * @param string $message
     */
    public function sendFeedback(Teacher $teacher, string $name, string $message): void;
}
