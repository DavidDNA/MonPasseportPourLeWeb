<?php

namespace App\Entity;

use App\Repository\StudentCompletesSessionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StudentCompletesSessionsRepository::class)]
class StudentCompletesSessions {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['status_update'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['status_update'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'progression')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Session $session = null;

    public function __construct() {
        $this->status = "";
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function getStudent(): ?Student {
        return $this->student;
    }

    public function setStudent(?Student $student): self {
        $this->student = $student;
        return $this;
    }

    public function getSession(): ?Session {
        return $this->session;
    }

    public function setSession(?Session $session): self {
        $this->session = $session;
        return $this;
    }

    public function upgrade(): self {
        switch ($this->status) {
            case SessionStatus::ToDo->value:
                $this->setStatus(SessionStatus::InProgress->value);
                break;
            case SessionStatus::InProgress->value:
                $this->setStatus(SessionStatus::Done->value);
                break;
            case SessionStatus::Done->value:
                $this->setStatus(SessionStatus::ToDo->value);
                break;
        }
        return $this;
    }
}
