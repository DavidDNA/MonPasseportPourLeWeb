<?php

namespace App\Session;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;

class SessionService implements SessionServiceInterface {

    public function __construct(private readonly EntityManagerInterface $entityManager) {
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function getSessions(): array {
        return $this->entityManager->getRepository(Session::class)->findAll();
    }

    /**
     * @inheritDoc
     * @param Session $session
     * @return Session
     */
    public function createSession(Session $session): Session {
        $this->entityManager->persist($session);
        $this->entityManager->flush();
        return $session;
    }

    /**
     * @inheritDoc
     * @param Session $session
     * @return Session
     */
    public function saveSession(Session $session): Session {
        $this->entityManager->flush();
        return $session;
    }

    /**
     * @inheritDoc
     * @param Session $session
     * @return void
     */
    public function removeSession(Session $session): void {
        $this->entityManager->remove($session);
        $this->entityManager->flush();
    }
}
