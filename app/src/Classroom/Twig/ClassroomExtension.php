<?php

namespace App\Classroom\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ClassroomExtension extends AbstractExtension {

    public function getFilters(): array {
        return [
            new TwigFilter('studentPasswordCharacter', [$this, 'getPasswordClass']),
        ];
    }

    public function getPasswordClass(array $character): string {
        return implode('', $character);
    }
}
