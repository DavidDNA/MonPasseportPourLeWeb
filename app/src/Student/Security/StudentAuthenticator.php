<?php

namespace App\Student\Security;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class StudentAuthenticator extends AbstractLoginFormAuthenticator {

    private EntityManagerInterface $entityManager;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager,
                                UrlGeneratorInterface  $urlGenerator) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Gets the URL where the students will log in.
     *
     * @param Request $request
     * @return string
     */
    protected function getLoginUrl(Request $request): string {
        return "/student";
    }

    /**
     * Authenticates the student.
     *
     * @param Request $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport {
        $classroomId = $request->request->get('classroom');
        $password = $request->request->get('password');

        /** @var Student $user */
        $user = $this->entityManager->getRepository(Student::class)->findOneBy([
            "password" => $password,
            "classroom" => $classroomId
        ]);

        if ($user === null) {
            throw new CustomUserMessageAuthenticationException('student.login.error');
        }
        return new Passport(new UserBadge(strval($user->getId())), new PasswordCredentials($password));
    }

    /**
     * Handles a successful authentication.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
        $data = [
            "url" => $this->urlGenerator->generate('student_main')
        ];
        return new Response(json_encode($data), Response::HTTP_OK);
    }

    /**
     * Handle an unsuccessful authentication.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response {
        $data = [
            "url" => $this->urlGenerator->generate('student_login')
        ];
        return new Response(json_encode($data), Response::HTTP_BAD_REQUEST);
    }
}
