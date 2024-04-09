<?php

namespace App\Classroom\Controller;

use App\Classroom\ClassroomServiceInterface;
use App\Classroom\Form\ClassroomAddStudent;
use App\Classroom\Form\ClassroomDelete;
use App\Classroom\Form\ClassroomStudentState;
use App\Classroom\Form\CreateClassroomFlow;
use App\Classroom\Security\ClassroomVoter;
use App\Entity\Classroom;
use App\Entity\ClassroomHasSessions;
use App\Entity\ClassroomWizard;
use App\Entity\SessionStatus;
use App\Entity\Student;
use App\Entity\StudentCompletesSessions;
use App\Entity\Teacher;
use App\Teacher\TeacherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClassroomController extends AbstractController {

    /**
     * Displays the user's created classrooms.
     *
     * @param ClassroomServiceInterface $classroomService
     * @param TeacherService $teacherService
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom', name: 'teacher_classrooms')]
    public function classrooms(ClassroomServiceInterface $classroomService, TeacherService $teacherService): Response {
        /** @var Teacher $teacher */
        $teacher = $this->getUser();

        if (!$teacher->isOnBoard()) {

            $teacherService->board($teacher);

            // ignore teachers already having existing classrooms
            if ($teacher->getClassrooms()->count() === 0) {
                return $this->redirect($this->generateUrl('teacher_classroom_new', ["onboarding" => true]));
            }
        }
        return $this->render('classroom/list.html.twig', [
            'classrooms' => $classroomService->getClassroomsOf($teacher)
        ]);
    }

    /**
     * Initiates the classroom creation wizard.
     *
     * @param Request $request
     * @param CreateClassroomFlow $flow
     * @param ClassroomServiceInterface $classroomService
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/new', name: 'teacher_classroom_new')]
    public function createClassroom(Request $request, CreateClassroomFlow $flow, ClassroomServiceInterface $classroomService): Response {
        $wizard = new ClassroomWizard();
        $onboarding = $request->query->get('onboarding');

        /** @var Teacher $teacher */
        $teacher = $this->getUser();
        $classroomCreated = null;

        $flow->bind($wizard);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                $classroomService->createClassroom($wizard, $teacher);
                $flow->reset();
                $classroomCreated = $wizard->getClassroom();
            }
        }

        return $this->render('classroom/new.html.twig', [
            'form' => $form->createView(),
            'flow' => $flow,
            'wizard' => $wizard,
            'classroomCreated' => $classroomCreated,
            'onboarding' => (bool)$onboarding
        ]);
    }

    /**
     * Displays the classroom details given its id.
     *
     * @param Classroom $classroom
     * @param Request $request
     * @param ClassroomServiceInterface $classroomService
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}', name: 'teacher_classroom')]
    public function classroom(Classroom $classroom, Request $request, ClassroomServiceInterface $classroomService): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::VIEW, $classroom);

        $addStudentForm = $this->createForm(ClassroomAddStudent::class, $classroom);
        $addStudentForm->handleRequest($request);

        $deleteClassroomForm = $this->createForm(ClassroomDelete::class, $classroom);
        $deleteClassroomForm->handleRequest($request);

        if ($addStudentForm->isSubmitted() && $addStudentForm->isValid()) {
            $classroomService->createStudent($classroom);
            $this->addFlash('success', 'classroom.detail.add_student.success');
            return $this->redirectToRoute('teacher_classroom', ['id' => $classroom->getId()]);
        }

        if ($deleteClassroomForm->isSubmitted() && $deleteClassroomForm->isValid()) {
            $classroomService->deleteClassroom($classroom);
            return $this->redirectToRoute('teacher_classrooms');
        }

        return $this->render('classroom/detail.html.twig', [
            'classroom' => $classroom,
            'addStudentForm' => $addStudentForm,
            'deleteForm' => $deleteClassroomForm
        ]);
    }

    /**
     * Displays a classroom's sessions plan.
     *
     * @param Classroom $classroom
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}/sessions', name: 'teacher_classroom_sessions')]
    public function classroomSessionPlan(Classroom $classroom): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::VIEW, $classroom);
        return $this->render('classroom/session-plan.html.twig', [
            "classroom" => $classroom
        ]);
    }

    /**
     * Displays a classroom's portfolio template.
     *
     * @param Classroom $classroom
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}/portfolio', name: 'teacher_classroom_portfolio')]
    public function classroomPortfolioTemplate(Classroom $classroom): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::VIEW, $classroom);
        return $this->render('classroom/portfolio.html.twig', [
            "classroom" => $classroom
        ]);
    }

    /**
     * Displays a given student's data.
     *
     * @param Request $request
     * @param Classroom $classroom
     * @param Student $student
     * @param ClassroomServiceInterface $classroomService
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}/student/{student}', name: 'teacher_classroom_student_details')]
    public function classroomStudentDetails(Request $request, Classroom $classroom, Student $student, ClassroomServiceInterface $classroomService): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::VIEW, $classroom);
        $stateForm = $this->createForm(ClassroomStudentState::class, $student, [
            'action' => $this->generateUrl('teacher_classroom_student_details', ["id" => $classroom->getId(), "student" => $student->getId()])
        ]);
        $stateForm->handleRequest($request);

        if ($stateForm->isSubmitted() && $stateForm->isValid()) {
            $classroomService->toggleStudentState($student);
            return $this->redirect($this->generateUrl('teacher_classroom', ["id" => $classroom->getId()]));
        }

        return $this->render('classroom/student-details.html.twig', [
            "classroom" => $classroom,
            "student" => $student,
            "stateForm" => $stateForm
        ]);
    }

    /**
     * Displays a given student's passport.
     *
     * @param Request $request
     * @param Classroom $classroom
     * @param Student $student
     * @param ClassroomServiceInterface $classroomService
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}/student/{student}/passport', name: 'teacher_classroom_student_passport')]
    public function classroomStudentPassport(Request $request, Classroom $classroom, Student $student, ClassroomServiceInterface $classroomService): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::VIEW, $classroom);

        return $this->render('classroom/student-passport.html.twig', [
            "student" => $student
        ]);
    }

    /**
     * Displays the classroom students accesses ready to be printed.
     *
     * @param Classroom $classroom
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}/print', name: 'teacher_classroom_print')]
    public function classroomPrint(Classroom $classroom, Request $request): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::VIEW, $classroom);
        $mode = $request->query->get('mode');
        return $this->render('classroom/print.html.twig', [
            'classroom' => $classroom,
            'mode' => $mode
        ]);
    }

    /**
     * Removes a session from the classroom.
     *
     * @param ClassroomHasSessions $session
     * @param ClassroomServiceInterface $classroomService
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}/remove-session/{session}', name: 'teacher_classroom_remove_session')]
    public function classroomRemoveSession(ClassroomHasSessions $session, ClassroomServiceInterface $classroomService, Request $request): Response {
        $classroom = $session->getClassroom();
        $this->denyAccessUnlessGranted(ClassroomVoter::EDIT, $classroom);
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('remove-session', $submittedToken)) {
            $classroomService->removeSession($session);
        }
        return $this->redirectToRoute('teacher_classroom', ['id' => $classroom->getId()]);
    }

    /**
     * Upgrades the progression.
     *
     * @param StudentCompletesSessions $progression
     * @param ClassroomServiceInterface $classroomService
     * @param SerializerInterface $serializer
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/upgrade/{id}', name: 'teacher_classroom_upgrade_progression')]
    public function classroomUpgradeProgression(StudentCompletesSessions $progression, ClassroomServiceInterface $classroomService, SerializerInterface $serializer, Request $request): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::EDIT, $progression->getStudent()->getClassroom());
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('upgrade-progression', $submittedToken)) {
            $classroomService->upgradeProgression($progression);
        }
        return new Response($serializer->serialize($progression, 'json', ['groups' => 'status_update']));
    }

    /**
     * Upgrades multiple progressions at one time.
     *
     * @param Classroom $classroom
     * @param ClassroomHasSessions $session
     * @param ClassroomServiceInterface $classroomService
     * @param SerializerInterface $serializer
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/teacher/me/classroom/{id}/bulk-upgrade/{session}', name: 'teacher_classroom_bulk_upgrade_progression')]
    public function classroomBulkUpgradeProgression(Classroom $classroom, ClassroomHasSessions $session, ClassroomServiceInterface $classroomService, SerializerInterface $serializer, Request $request): Response {
        $this->denyAccessUnlessGranted(ClassroomVoter::EDIT, $classroom);
        $submittedToken = $request->request->get('token');
        $status = $request->request->get('status');
        if ($this->isCsrfTokenValid('upgrade-progression', $submittedToken)) {
            $updatedProgresses = $classroomService->bulkUpgradeProgression($classroom, $session, SessionStatus::fromString($status));
            return new Response($serializer->serialize($updatedProgresses, 'json', ['groups' => 'status_update']));
        }
        return new Response("invalid csrf token", Response::HTTP_BAD_REQUEST);
    }
}
