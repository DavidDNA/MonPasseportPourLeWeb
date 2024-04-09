<?php

namespace App\Classroom;

use App\Entity\AvatarHasUpgrades;
use App\Entity\AvatarUpgrade;
use App\Entity\Classroom;
use App\Entity\ClassroomHasSessions;
use App\Entity\ClassroomWizard;
use App\Entity\SessionStatus;
use App\Entity\Student;
use App\Entity\StudentCompletesSessions;
use App\Entity\Teacher;
use App\Student\Utils\StudentRandomPasswordGeneratorInterface;
use App\Utils\RandomPasswordGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class ClassroomService implements ClassroomServiceInterface {

    private const MaxClassroomGenerationTries = 10;
    private const MaxPasswordGenerationTries = 10;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RandomPasswordGeneratorInterface $classroomCodeGenerator
     * @param StudentRandomPasswordGeneratorInterface $studentPasswordGenerator
     */
    public function __construct(private readonly EntityManagerInterface                  $entityManager,
                                private readonly RandomPasswordGeneratorInterface        $classroomCodeGenerator,
                                private readonly StudentRandomPasswordGeneratorInterface $studentPasswordGenerator) {
    }

    /**
     * @inhericDoc
     * @param ClassroomWizard $wizard
     * @param Teacher $teacher
     * @return Classroom
     */
    public function createClassroom(ClassroomWizard $wizard, Teacher $teacher): Classroom {
        $classroom = $wizard->getClassroom();

        // sets the classroom's owner
        $classroom->setTeacher($teacher);

        // CAUTION: will have collisions if too many classrooms
        // TODO: prevent collisions after releasing proof of concept version
        $classroom->setCode($this->classroomCodeGenerator->getRandomPassword(Classroom::CodeLength));

        // build the relations between the classroom and the chosen sessions
        foreach ($wizard->getSessions() as $session) {
            $classroom->addSession(new ClassroomHasSessions($classroom, $session));
        }

        // create students accesses
        for ($i = 0; $i < $wizard->getStudentsAmount(); $i++) {
            $this->addStudent($classroom, $wizard->isBlockResourcesAccess() ? SessionStatus::ToDo : SessionStatus::InProgress);
        }

        $this->entityManager->persist($classroom);
        $this->entityManager->flush();
        return $classroom;
    }

    /**
     * @inheritDoc
     * @param Teacher $teacher
     * @return array
     */
    public function getClassroomsOf(Teacher $teacher): array {
        return $this->entityManager->getRepository(Classroom::class)->findBy([
            'teacher' => $teacher
        ], ['id' => 'DESC']);
    }

    /**
     * @inheritDoc
     * @param Classroom $classroom
     * @return Classroom
     */
    public function createStudent(Classroom $classroom): Classroom {
        $this->addStudent($classroom, SessionStatus::ToDo);
        $this->entityManager->flush();
        return $classroom;
    }

    /**
     * Adds a student to the given classroom with a
     * random and unique password.
     *
     * @param Classroom $classroom
     * @param SessionStatus $defaultStatus
     * @return void
     */
    private function addStudent(Classroom $classroom, SessionStatus $defaultStatus): void {
        $allAccesses = $classroom->getStudents()->map(function (Student $student) {
            return $student->getPassword();
        });

        // get a unique password for the student
        // CAUTION: will have password collisions if too many students and not enough password characters
        $tries = 0;
        do {
            $tries++;
            $password = $this->studentPasswordGenerator->getRandomPassword(Student::PasswordLength);
            if ($tries > self::MaxPasswordGenerationTries) {
                break;
            }
        } while ($allAccesses->contains($password));

        $defaultUpgrades = $this->entityManager->getRepository(AvatarUpgrade::class)->findBy([
            "name" => AvatarUpgrade::DefaultName
        ]);

        $student = new Student($password);
        $student->createDefaultAvatar();

        /** @var AvatarUpgrade $upgrade */
        foreach ($defaultUpgrades as $upgrade) {
            $avatarUpgrade = new AvatarHasUpgrades();
            $avatarUpgrade->setUpgrade($upgrade);
            $avatarUpgrade->setChoice("1");
            $this->entityManager->persist($avatarUpgrade);
            $student->getAvatar()->addUpgrade($avatarUpgrade);
        }

        foreach ($classroom->getSessions() as $session) {
            $progression = new StudentCompletesSessions();
            $progression->setStudent($student);
            $progression->setSession($session->getSession());
            $progression->setStatus($defaultStatus->value);
            $student->addProgression($progression);
        }
        $classroom->addStudent($student);
    }

    /**
     * @inheritDoc
     * @param Classroom $classroom
     * @return void
     */
    public function deleteClassroom(Classroom $classroom): void {
        $this->entityManager->remove($classroom);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     * @param StudentCompletesSessions $progression
     * @return void
     */
    public function upgradeProgression(StudentCompletesSessions $progression): void {
        $progression->upgrade();
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     * @param Classroom $classroom
     * @param ClassroomHasSessions $session
     * @param SessionStatus $status
     * @return array
     */
    public function bulkUpgradeProgression(Classroom $classroom, ClassroomHasSessions $session, SessionStatus $status): array {
        $progressions = $classroom->getStudents()->map(function (Student $student) use ($session) {
            return $student->getProgressionForSession($session->getSession());
        });

        /** @var StudentCompletesSessions $progression */
        foreach ($progressions as $progression) {
            $progression->setStatus($status->value);
        }
        $this->entityManager->flush();
        return $progressions->toArray();
    }

    /**
     * @inheritDoc
     * @param string $code
     * @return Classroom|null
     */
    public function getClassroomByCode(string $code): ?Classroom {
        return $this->entityManager->getRepository(Classroom::class)->findOneBy([
            "code" => $code
        ]);
    }

    /**
     * @inheritDoc
     * @param Student $student
     * @return void
     */
    public function toggleStudentState(Student $student): void {
        $student->toggleState();
        $this->entityManager->flush();
    }

    /**
     * @inhericDoc
     * @param ClassroomHasSessions $session
     * @return void
     */
    public function removeSession(ClassroomHasSessions $session): void {
        $classroom = $session->getClassroom();
        $classroom->removeSession($session);
        foreach ($classroom->getStudents() as $student) {
            $student->removeSession($session->getSession());
        }
        $this->entityManager->flush();
    }
}
