<?php

namespace App\Classroom\Security;

use App\Entity\Classroom;
use App\Entity\Teacher;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ClassroomVoter extends Voter {

    public const VIEW = 'view';
    public const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool {
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof Classroom;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        $user = $token->getUser();

        if (!$user instanceof Teacher) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var Classroom $classroom */
        $classroom = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($classroom, $user),
            self::EDIT => $this->canEdit($classroom, $user),
            default => throw new LogicException('Attribute not supported')
        };
    }

    private function canView(Classroom $classroom, Teacher $teacher): bool {
        return $teacher === $classroom->getTeacher();
    }

    private function canEdit(Classroom $classroom, Teacher $teacher): bool {
        return $teacher === $classroom->getTeacher();
    }
}
