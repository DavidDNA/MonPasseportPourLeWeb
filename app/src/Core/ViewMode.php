<?php

namespace App\Core;

enum ViewMode: string {

    case Normal = "normal";

    case Print = "print";

    /**
     * Returns the enum given the value or a default value.
     * @param string|null $value
     * @return ViewMode
     */
    static function fromOrDefault(?string $value): ViewMode {
        return ViewMode::tryfrom($value) ?? ViewMode::Normal;
    }
}
