<?php

namespace App\Session\Security;

use App\Entity\Session;
use App\Entity\Teacher;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SessionVoter extends Voter {

    public const CREATE = 'create';
    public const EDIT = 'edit';

    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool {
        return in_array($attribute, [self::CREATE, self::EDIT])
            && $subject instanceof Session;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        $user = $token->getUser();

        if (!$user instanceof Teacher) {
            return false;
        }

        /** @var Session $session */
        $session = $subject;

        return match ($attribute) {
            self::CREATE => $this->canCreate(),
            self::EDIT => $this->canEdit($session, $user),
            default => throw new LogicException('Attribute not supported')
        };
    }

    private function canCreate(): bool {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    private function canEdit(Session $session, Teacher $teacher): bool {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
