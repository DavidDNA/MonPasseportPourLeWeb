<?php

namespace App\Teacher\Controller;

use App\Entity\Teacher;
use App\Form\RegistrationFormType;
use App\Teacher\Form\TeacherFeedbackType;
use App\Teacher\Form\TeacherPasswordRecoveryType;
use App\Teacher\Form\TeacherUpdatePasswordType;
use App\Teacher\TeacherServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class TeacherController extends AbstractController {

    /**
     * Main entry for teachers. Provides a login form.
     * Will redirect the user if already authenticated.
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/teacher', name: 'teacher_main')]
    public function main(AuthenticationUtils $authenticationUtils): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $user = $this->getUser();

        if ($user !== null) {
            return $this->redirectToRoute('teacher_classrooms');
        } else {
            return $this->render('teacher/login.html.twig', [
                'error' => $error,
            ]);
        }
    }

    /**
     * Creates a new teacher. Endpoint for both form rendering
     * and form validation.
     *
     * @param Request $request
     * @param TeacherServiceInterface $teacherService
     * @return Response
     */
    #[Route('/teacher/new', name: 'teacher_new')]
    public function new(Request $request, TeacherServiceInterface $teacherService): Response {
        $created = false;
        $user = new Teacher();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teacherService->createAccount($user);
            $created = true;
        }

        return $this->render('teacher/new.html.twig', [
            'registrationForm' => $form->createView(),
            'created' => $created
        ]);
    }

    /**
     * Displays the terms and conditions
     *
     * @return Response
     */
    #[Route('/teacher/terms-of-use', name: 'teacher_terms')]
    public function termsOfUse(): Response {
        return $this->render('teacher/terms.html.twig');
    }

    /**
     * Shows the teacher's account details.
     *
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param TranslatorInterface $translator
     * @param TeacherServiceInterface $teacherService
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/teacher/me/account', name: 'teacher_account')]
    public function account(UserPasswordHasherInterface $userPasswordHasher, TranslatorInterface $translator, TeacherServiceInterface $teacherService, Request $request): Response {
        /** @var Teacher $teacher */
        $teacher = $this->getUser();
        $form = $this->createForm(TeacherUpdatePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            if ($userPasswordHasher->isPasswordValid($teacher, $oldPassword)) {
                $teacherService->changePassword($teacher, $newPassword);
                $this->addFlash('success', 'teacher.update.password.success');
                return $this->redirectToRoute('teacher_account');
            } else {
                $form->get('currentPassword')->addError(new FormError($translator->trans('teacher.update.password.error_current')));
            }
        }

        return $this->render('teacher/account.html.twig', [
            "teacher" => $teacher,
            "updatePasswordForm" => $form
        ]);
    }

    /**
     * Shows a static help page.
     *
     * @return Response
     */
    #[Route('/teacher/me/help', name: 'teacher_help')]
    public function help(): Response {
        return $this->render('teacher/help.html.twig');
    }

    /**
     * Renders a form that allow teachers to send feedbacks.
     *
     * @param Request $request
     * @param TeacherServiceInterface $teacherService
     * @return Response
     */
    #[Route('/teacher/me/feedback', name: 'teacher_feedback')]
    public function feedback(Request $request, TeacherServiceInterface $teacherService): Response {
        $form = $this->createForm(TeacherFeedbackType::class);
        $form->handleRequest($request);
        $sent = false;

        /** @var Teacher $teacher */
        $teacher = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $teacherService->sendFeedback($teacher, $form->get('name')->getData(), $form->get('message')->getData());
                $this->addFlash('success', 'teacher.feedback.success');
                $sent = true;
            } catch (Exception $e) {
                $this->addFlash('error', 'teacher.feedback.error');
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('teacher/feedback.html.twig', [
            "form" => $form,
            "sent" => $sent
        ]);
    }

    /**
     * Initiates a password recovery for a teacher account.
     *
     * @param Request $request
     * @param TeacherServiceInterface $teacherService
     * @return Response
     */
    #[Route('/teacher/recovery', name: 'teacher_recovery')]
    public function passwordRecovery(Request $request, TeacherServiceInterface $teacherService): Response {
        $form = $this->createForm(TeacherPasswordRecoveryType::class);
        $form->handleRequest($request);
        $sent = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            try {
                $teacherService->recoverPassword($email);
                $this->addFlash('success', 'teacher.recovery.success');
                $sent = true;
            } catch (Exception $e) {
                $this->addFlash('error', 'teacher.recovery.error');
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('teacher/password-recovery.html.twig', [
            "form" => $form,
            "sent" => $sent
        ]);
    }

    /**
     * Route to verify an email address after an account has been created.
     *
     * @param Request $request
     * @param TeacherServiceInterface $teacherService
     * @return Response
     */
    #[Route('/teacher/verify', name: 'teacher_verify_email')]
    public function verify(Request $request, TeacherServiceInterface $teacherService): Response {
        $id = $request->get('id');
        if ($id !== null && $teacherService->verifyEmail($id, $request->getUri())) {
            $this->addFlash('success', 'teacher.verify.success');
        } else {
            $this->addFlash('error', 'teacher.verify.error');
        }
        return $this->redirectToRoute('teacher_main');
    }
}
