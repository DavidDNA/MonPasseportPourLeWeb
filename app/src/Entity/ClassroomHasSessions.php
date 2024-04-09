<?php

namespace App\Entity;

use App\Repository\ClassroomHasSessionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomHasSessionsRepository::class)]
class ClassroomHasSessions {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $annotation = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Session $session = null;

    public function __construct(Classroom $classroom, Session $session) {
        $this->classroom = $classroom;
        $this->session = $session;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getAnnotation(): ?string {
        return $this->annotation;
    }

    public function setAnnotation(?string $annotation): self {
        $this->annotation = $annotation;

        return $this;
    }

    public function getClassroom(): ?Classroom {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self {
        $this->classroom = $classroom;

        return $this;
    }

    public function getSession(): ?Session {
        return $this->session;
    }

    public function setSession(?Session $session): self {
        $this->session = $session;

        return $this;
    }
}
