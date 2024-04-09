<?php

namespace App\Utils;

use Exception;
use function strlen;

class RandomPasswordGenerator implements RandomPasswordGeneratorInterface {

    /**
     * @inhericDoc
     *
     * Implementation taken from Laravel.
     * https://github.com/laravel/framework/blob/9.x/src/Illuminate/Support/Str.php
     *
     * @param int $length
     * @return string
     * @throws Exception
     */
    public function getRandomPassword(int $length): string {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}
