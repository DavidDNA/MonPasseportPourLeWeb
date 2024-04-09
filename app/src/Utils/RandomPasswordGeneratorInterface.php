<?php

namespace App\Utils;

interface RandomPasswordGeneratorInterface {

    /**
     * Generates a random password of the given byte length.
     *
     * @param int $length
     * @return string
     */
    public function getRandomPassword(int $length): string;
}
