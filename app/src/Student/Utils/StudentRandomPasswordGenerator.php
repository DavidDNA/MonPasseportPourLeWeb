<?php

namespace App\Student\Utils;

use Exception;

class StudentRandomPasswordGenerator implements StudentRandomPasswordGeneratorInterface {

    /**
     * Available shapes.
     */
    private const Shapes = ["s", "c", "t", "d"];

    /**
     * Available colors.
     */
    private const Colors = ["y", "r", "p", "b"];

    /**
     * @inhericDoc
     * @param int $length
     * @return string
     * @throws Exception
     */
    public function getRandomPassword(int $length): string {
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $password .= self::Shapes[random_int(0, count(self::Shapes) - 1)] . self::Colors[random_int(0, count(self::Colors) - 1)];
        }
        return $password;
    }
}
