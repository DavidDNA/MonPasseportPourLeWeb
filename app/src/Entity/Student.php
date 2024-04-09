<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student implements UserInterface, PasswordAuthenticatedUserInterface {

    const PasswordLength = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: StudentCompletesSessions::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $progression;

    #[ORM\OneToOne(mappedBy: 'student', cascade: ['persist', 'remove'])]
    private ?Avatar $avatar = null;

    #[ORM\Column]
    private bool $enabled = true;

    public function __construct(string $password) {
        $this->progression = new ArrayCollection();
        $this->password = $password;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function getClassroom(): ?Classroom {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self {
        $this->classroom = $classroom;
        return $this;
    }

    public function getProgression(): Collection {
        return $this->progression;
    }

    public function addProgression(StudentCompletesSessions $progression): self {
        if (!$this->progression->contains($progression)) {
            $this->progression->add($progression);
            $progression->setStudent($this);
        }
        return $this;
    }

    public function removeProgression(StudentCompletesSessions $progression): self {
        if ($this->progression->removeElement($progression)) {
            // set the owning side to null (unless already changed)
            if ($progression->getStudent() === $this) {
                $progression->setStudent(null);
            }
        }
        return $this;
    }

    public function getAvatar(): ?Avatar {
        return $this->avatar;
    }

    public function setAvatar(Avatar $avatar): self {
        // set the owning side of the relation if necessary
        if ($avatar->getStudent() !== $this) {
            $avatar->setStudent($this);
        }

        $this->avatar = $avatar;

        return $this;
    }

    public function getProgressionForSession(Session $session): StudentCompletesSessions {
        return $this->progression->filter(function (StudentCompletesSessions $progression) use ($session) {
            return $progression->getSession()->getId() === $session->getId();
        })->first();
    }

    public function getSessionsDone(): int {
        return $this->progression->filter(function (StudentCompletesSessions $progression) {
            return $progression->getStatus() === SessionStatus::Done->value;
        })->count();
    }

    public function getRoles(): array {
        $roles[] = 'ROLE_STUDENT';
        return array_unique($roles);
    }

    public function eraseCredentials() {
    }

    public function getUserIdentifier(): string {
        return $this->id;
    }

    public function getDoneSessionsAmount(): int {
        return $this->progression->filter(function (StudentCompletesSessions $p) {
            return $p->getStatus() === SessionStatus::Done->value;
        })->count();
    }

    public function getRelativeProgression(): float {
        return $this->getDoneSessionsAmount() / $this->progression->count();
    }

    public function hasPassport(): bool {
        return $this->getDoneSessionsAmount() === $this->progression->count();
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;
        return $this;
    }

    public function toggleState(): void {
        $this->enabled = !$this->enabled;
    }

    public function createDefaultAvatar(): void {
        $this->avatar = new Avatar();
        $this->avatar->setStudent($this);
    }

    public function getUpgradesNames(): array {
        return $this->getAvatar()->getUpgradesNames();
    }

    public function removeSession(Session $session): void {
        /** @var StudentCompletesSessions $studentSession */
        $studentSession = null;
        for ($i = 0; $i < $this->progression->count(); $i++) {
            /** @var StudentCompletesSessions $candidate */
            $candidate = $this->progression[$i];
            if ($candidate->getSession()->getId() === $session->getId()) {
                $studentSession = $candidate;
                break;
            }
        }
        if ($studentSession) {
            $this->progression->removeElement($studentSession);
        }
    }
}
