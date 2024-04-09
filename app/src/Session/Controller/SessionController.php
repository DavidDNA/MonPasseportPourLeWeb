<?php

namespace App\Session\Controller;

use App\Entity\Session;
use App\Session\Form\SessionDelete;
use App\Session\Form\SessionType;
use App\Session\Security\SessionVoter;
use App\Session\SessionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController {

    /**
     * Displays all the platform sessions.
     *
     * @param SessionServiceInterface $sessionService
     * @return Response
     */
    #[Route('/teacher/me/session', name: 'sessions')]
    public function sessions(SessionServiceInterface $sessionService): Response {
        return $this->render('sessions/list.html.twig', [
            'sessions' => $sessionService->getSessions()
        ]);
    }

    /**
     * Creates a new session.
     *
     * @param SessionServiceInterface $sessionService
     * @param Request $request
     * @return Response
     */
    #[Route('/teacher/me/session/new', name: 'session_new')]
    public function createSession(SessionServiceInterface $sessionService, Request $request): Response {
        $session = new Session();
        $this->denyAccessUnlessGranted(SessionVoter::CREATE, $session);
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionService->createSession($session);
            return $this->redirectToRoute('session', ['id' => $session->getId()]);
        }

        return $this->render('sessions/detail.html.twig', [
            'session' => $session,
            'form' => $form
        ]);
    }

    /**
     * Displays the details of a session and lets the user edit it.
     *
     * @param Session $session
     * @param SessionServiceInterface $sessionService
     * @param Request $request
     * @return Response
     */
    #[Route('/teacher/me/session/{id}', name: 'session')]
    public function session(Session $session, SessionServiceInterface $sessionService, Request $request): Response {
        $this->denyAccessUnlessGranted(SessionVoter::EDIT, $session);
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        $deleteForm = $this->createForm(SessionDelete::class, $session);
        $deleteForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionService->saveSession($session);
        }

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $sessionService->removeSession($session);
            return $this->redirectToRoute('sessions');
        }

        return $this->render('sessions/detail.html.twig', [
            'session' => $session,
            'form' => $form,
            'deleteForm' => $deleteForm
        ]);
    }

    /**
     * Shows the session details as readonly.
     *
     * @param Session $session
     *
     * @return Response
     */
    #[Route('/session/{id}', name: 'session_detail')]
    public function sessionDetails(Session $session): Response {
        return $this->render('sessions/sheet.html.twig', [
            'session' => $session
        ]);
    }

    /**
     * Shows the student resources.
     *
     * @param Session $session
     * @return Response
     */
    #[Route('/session/student/{id}', name: 'session_student_resources')]
    public function sessionStudentResources(Session $session): Response {
        return $this->render('sessions/student-resources.html.twig', [
            'session' => $session
        ]);
    }
}
