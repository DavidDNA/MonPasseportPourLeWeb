<?php

namespace App\Entity;

enum SessionStatus: string {
    case ToDo = "todo";
    case InProgress = "in_progress";
    case Done = "done";

    /**
     * Returns a SessionStatus from a string.
     *
     * @param string $string
     * @return SessionStatus|null
     */
    public static function fromString(string $string): ?SessionStatus {
        return match ($string) {
            self::ToDo->value => self::ToDo,
            self::InProgress->value => self::InProgress,
            self::Done->value => self::Done,
            default => null
        };
    }
}
