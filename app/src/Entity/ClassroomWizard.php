<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class ClassroomWizard {

    /**
     * Chosen sessions. The sessions will be then converted to
     * ClassroomHasSession entities and linked to the classroom.
     */
    private Collection $sessions;

    /**
     * How many students (accesses) have to be created ?
     */
    #[Assert\Range(
        min: 0,
        max: 150,
        groups: ['Default']
    )]
    private int $studentsAmount;

    /**
     * The classroom's base model with some values already
     * set during the wizard.
     */
    private Classroom $classroom;

    /**
     * This flag will define the default status of sessions.
     */
    private bool $blockResourcesAccess = true;

    public function __construct() {
        $this->sessions = new ArrayCollection();
        $this->classroom = new Classroom();
    }

    /**
     * @param Collection $sessions
     */
    public function setSessions(Collection $sessions): void {
        $this->sessions = $sessions;
    }

    /**
     * @return Collection
     */
    public function getSessions(): Collection {
        return $this->sessions;
    }

    /**
     * @return int
     */
    public function getStudentsAmount(): int {
        return $this->studentsAmount;
    }

    /**
     * @param int $studentsAmount
     */
    public function setStudentsAmount(int $studentsAmount): void {
        $this->studentsAmount = $studentsAmount;
    }

    /**
     * @return Classroom
     */
    public function getClassroom(): Classroom {
        return $this->classroom;
    }

    /**
     * @param bool $blockResourcesAccess
     * @return ClassroomWizard
     */
    public function setBlockResourcesAccess(bool $blockResourcesAccess): ClassroomWizard {
        $this->blockResourcesAccess = $blockResourcesAccess;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBlockResourcesAccess(): bool {
        return $this->blockResourcesAccess;
    }
}
