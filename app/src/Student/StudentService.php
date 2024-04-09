<?php

namespace App\Student;

use App\Entity\AvatarHasUpgrades;
use App\Entity\AvatarUpgrade;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class StudentService implements StudentServiceInterface {

    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly TranslatorInterface    $translator) {
    }

    /**
     * @inheritdoc
     * @param Student $student
     * @return array
     */
    public function getUpgrades(Student $student): array {
        $yearGroup = $student->getClassroom()->getYearGroup();
        $allUpgrades = $this->entityManager->getRepository(AvatarUpgrade::class)->findall();
        // remove the default upgrade
        $allUpgrades = array_filter($allUpgrades, fn($item) => !$item->IsDefault());
        // get all upgrades depending on the year group of the student's class
        $allUpgrades = array_slice($allUpgrades, 0, min($yearGroup->getAvatarUpgrades(), count($allUpgrades)));
        // set relative thresholds
        $upgradeAmount = count($allUpgrades);
        /** @var AvatarUpgrade $upgrade */
        for ($i = 0; $i < $upgradeAmount; $i++) {
            $upgrade = $allUpgrades[$i];
            $upgrade->setRelativeThreshold(($i + 1) / $upgradeAmount);
        }
        return $allUpgrades;

    }

    /**
     * @inhericDoc
     * @param Student $student
     * @return AvatarUpgrade|null
     */
    public function getNextAvailableUpgrade(Student $student): ?AvatarUpgrade {
        $upgrades = $this->getUpgrades($student);
        // find upgrades to get only those who are (1) not already unlocked by the student
        // and (2) available
        $upgrades = array_values(array_filter($upgrades, fn(AvatarUpgrade $item) => !in_array($item->getName(), $student->getUpgradesNames()) && $student->getRelativeProgression() >= $item->getRelativeThreshold()));
        return count($upgrades) > 0 ? $upgrades[0] : null;
    }

    /**
     * @inhericDoc
     * @param Student $student
     * @param string $upgradeName
     * @param string $upgradeChoice
     * @return Student
     */
    public function upgradeAvatar(Student $student, string $upgradeName, string $upgradeChoice): Student {
        /** @var AvatarUpgrade $upgrade */
        $upgrade = $this->entityManager->getRepository(AvatarUpgrade::class)->findOneBy(["name" => $upgradeName]);
        if ($upgrade !== null && !$student->getAvatar()->hasUpgrade($upgrade->getName())) {
            $avatarUpgrade = new AvatarHasUpgrades();
            $avatarUpgrade->setUpgrade($upgrade);
            $avatarUpgrade->setChoice($upgradeChoice);
            $avatarUpgrade->setAvatar($student->getAvatar());
            $student->getAvatar()->addUpgrade($avatarUpgrade);

            $this->entityManager->persist($avatarUpgrade);
            $this->entityManager->flush();
        }
        return $student;
    }

    /**
     * @inhericDoc
     * @param array $upgrades
     * @return array
     */
    public function getUpgradesi18n(array $upgrades): array {
        $texts = [];
        /** @var AvatarUpgrade $upgrade */
        foreach ($upgrades as $upgrade) {
            $name = strtolower($upgrade->getName());
            $texts["{$upgrade->getType()}.$name"] = $this->translator->trans("student.avatar.upgrade.$name", [], "student");
        }
        return $texts;
    }
}
