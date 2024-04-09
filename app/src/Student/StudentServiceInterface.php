<?php

namespace App\Student;

use App\Entity\AvatarUpgrade;
use App\Entity\Student;
use Doctrine\Common\Collections\ArrayCollection;

interface StudentServiceInterface {

    /**
     * Returns all upgrades a student may have.
     *
     * @param Student $student
     * @return array
     */
    public function getUpgrades(Student $student): array;

    /**
     * Gets the next available avatar upgrade for the given student.
     *
     * @param Student $student
     * @return AvatarUpgrade|null
     */
    public function getNextAvailableUpgrade(Student $student): ?AvatarUpgrade;

    /**
     * Upgrades the student's avatar given the upgrade name and choice.
     *
     * The avatar won't be upgraded if the upgrade has already been made or
     * if it's not available yet.
     *
     * @param Student $student
     * @param string $upgradeName
     * @param string $upgradeChoice
     * @return Student
     */
    public function upgradeAvatar(Student $student, string $upgradeName, string $upgradeChoice): Student;

    /**
     * Returns an array containing all the translated texts for upgrades.
     * @param array $upgrades
     * @return array
     */
    public function getUpgradesi18n(array $upgrades): array;
}
