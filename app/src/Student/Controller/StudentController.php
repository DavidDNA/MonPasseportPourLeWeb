<?php

namespace App\Student\Controller;

use App\Classroom\ClassroomServiceInterface;
use App\Entity\AvatarUpgrade;
use App\Entity\Student;
use App\Student\StudentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StudentController extends AbstractController {

    /**
     * Student's login route.
     *
     * @return Response
     */
    #[Route('/student', name: 'student_login')]
    public function login(): Response {
        return $this->render('student/login.html.twig');
    }

    /**
     * Student's login route with classroom already set.
     *
     * @param string $code
     * @return Response
     */
    #[Route('/c/{code}', name: 'student_login_with_classroom')]
    public function loginWithClassroom(string $code): Response {
        return $this->render('student/login.html.twig', ["code" => $code]);
    }

    /**
     * Checks if a classroom exists given its code.
     *
     * @param string $code
     * @param SerializerInterface $serializer
     * @param ClassroomServiceInterface $classroomService
     * @return Response
     */
    #[Route('/student/login/classroom/{code}', name: 'student_login_classroom_check')]
    public function checkClassroom(string $code, SerializerInterface $serializer, ClassroomServiceInterface $classroomService): Response {
        $classroom = $classroomService->getClassroombyCode($code);
        if ($classroom === null) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        return new Response($serializer->serialize($classroom, 'json', ['groups' => 'header']));
    }

    /**
     * Redirects the student after logging out.
     *
     * @return Response
     */
    #[Route('/student/logout', name: 'student_logout')]
    public function logout(): Response {
        return new RedirectResponse($this->generateUrl("student_login"));
    }

    /**
     * Renders the student's main view. Feeds it with the next avatar's upgrade available.
     *
     * @param StudentServiceInterface $studentService
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/student/me', name: 'student_main')]
    public function main(StudentServiceInterface $studentService, SerializerInterface $serializer): Response {
        /** @var Student $student */
        $student = $this->getUser();
        $upgrades = array_filter($studentService->getUpgrades($student), function ($item) {
            return $item->getName() !== AvatarUpgrade::DefaultName;
        });
        $upgrade = $studentService->getNextAvailableUpgrade($student);
        return $this->render('student/main.html.twig', [
            "student" => $student,
            "upgrades" => $upgrades,
            "upgrade" => $upgrade ? $serializer->serialize($upgrade, 'json') : null,
            "upgradesi18n" => $serializer->serialize($studentService->getUpgradesi18n($upgrades), 'json')
        ]);
    }

    /**
     * Renders the student's passport.
     *
     * @return Response
     */
    #[Route('/student/me/passport', name: 'student_passport')]
    public function passport(): Response {
        /** @var Student $student */
        $student = $this->getUser();
        return $this->render('student/passport.html.twig', [
            "student" => $student
        ]);
    }

    /**
     * Upgrades the student's avatar.
     *
     * @param Request $request
     * @param StudentServiceInterface $studentService
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/student/me/upgrade', name: 'student_upgrade')]
    public function upgradeAvatar(Request $request, StudentServiceInterface $studentService, SerializerInterface $serializer): Response {
        $upgradeName = $request->request->get('upgrade-name');
        $upgradeChoice = $request->request->get('upgrade-choice');
        $token = $request->request->get('token');

        /** @var Student $student */
        $student = $this->getUser();

        if ($this->isCsrfTokenValid('upgrade-avatar', $token)) {
            $student = $studentService->upgradeAvatar($student, $upgradeName, $upgradeChoice);
        }

        return new Response($serializer->serialize($student->getAvatar(), 'json', ['groups' => 'avatar']));
    }
}

