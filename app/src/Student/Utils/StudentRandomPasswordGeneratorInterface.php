<?php

namespace App\Student\Utils;

interface StudentRandomPasswordGeneratorInterface {

    /**
     * Generates a random password of the given length.
     *
     * @param int $length
     * @return string
     */
    public function getRandomPassword(int $length): string;
}
