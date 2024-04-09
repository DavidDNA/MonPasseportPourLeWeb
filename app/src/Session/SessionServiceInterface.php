<?php

namespace App\Session;

use App\Entity\Session;

interface SessionServiceInterface {

    /**
     * Returns all the sessions available.
     *
     * @return array
     */
    public function getSessions(): array;

    /**
     * Persists the given session.
     *
     * @param Session $session
     * @return mixed
     */
    public function createSession(Session $session): Session;

    /**
     * Saves the given session.
     *
     * @param Session $session
     * @return Session
     */
    public function saveSession(Session $session): Session;

    /**
     * Removes the given session.
     *
     * @param Session $session
     * @return void
     */
    public function removeSession(Session $session): void;
}
