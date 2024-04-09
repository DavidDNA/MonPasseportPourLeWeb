<?php

namespace App\Classroom;

use App\Entity\Classroom;
use App\Entity\ClassroomHasSessions;
use App\Entity\ClassroomWizard;
use App\Entity\Session;
use App\Entity\SessionStatus;
use App\Entity\Student;
use App\Entity\StudentCompletesSessions;
use App\Entity\Teacher;

interface ClassroomServiceInterface {

    /**
     * Creates a new classroom for the given teacher. The classroom data
     * comes from a wizard model.
     *
     * @param ClassroomWizard $wizard
     * @param Teacher $teacher
     * @return Classroom
     */
    public function createClassroom(ClassroomWizard $wizard, Teacher $teacher): Classroom;

    /**
     * Gets all the classrooms of a teacher.
     *
     * @param Teacher $teacher
     * @return array
     */
    public function getClassroomsOf(Teacher $teacher): array;

    /**
     * Creates a new student for the given classroom.
     *
     * @param Classroom $classroom
     * @return Classroom
     */
    public function createStudent(Classroom $classroom): Classroom;

    /**
     * Deletes the given classroom.
     *
     * @param Classroom $classroom
     * @return void
     */
    public function deleteClassroom(Classroom $classroom): void;

    /**
     * Upgrade the progression to the next status.
     *
     * @param StudentCompletesSessions $progression
     * @return void
     */
    public function upgradeProgression(StudentCompletesSessions $progression): void;

    /**
     * Upgrades a session for all the student's classroom.
     *
     * @param Classroom $classroom
     * @param ClassroomHasSessions $session
     * @param SessionStatus $status
     * @return array
     */
    public function bulkUpgradeProgression(Classroom $classroom, ClassroomHasSessions $session, SessionStatus $status): array;

    /**
     * Returns a classroom (or null if none) given its code.
     *
     * @param string $code
     * @return Classroom|null
     */
    public function getClassroomByCode(string $code): ?Classroom;

    /**
     * Toggles the given student state (enabled / disabled).
     *
     * @param Student $student
     * @return void
     */
    public function toggleStudentState(Student $student): void;

    /**
     * Removes a session from a classroom.
     *
     * @param ClassroomHasSessions $session
     * @return void
     */
    public function removeSession(ClassroomHasSessions $session): void;
}
