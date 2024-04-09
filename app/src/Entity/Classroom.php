<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClassroomRepository::class)]
class Classroom {

    const CodeLength = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['header'])]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Groups(['header'])]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'classrooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null;

    #[ORM\OneToMany(mappedBy: 'classroom', targetEntity: Student::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $students;

    #[ORM\ManyToOne]
    private ?YearGroup $yearGroup = null;

    #[ORM\OneToMany(mappedBy: 'classroom', targetEntity: ClassroomHasSessions::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sessions;

    public function __construct() {
        $this->students = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getCode(): ?string {
        return $this->code;
    }

    public function setCode(string $code): self {
        $this->code = $code;
        return $this;
    }

    public function getTeacher(): ?Teacher {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self {
        $this->teacher = $teacher;
        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection {
        return $this->students;
    }

    public function addStudent(Student $student): self {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClassroom($this);
        }
        return $this;
    }

    public function removeStudent(Student $student): self {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClassroom() === $this) {
                $student->setClassroom(null);
            }
        }
        return $this;
    }

    public function getYearGroup(): ?YearGroup {
        return $this->yearGroup;
    }

    public function setYearGroup(?YearGroup $yearGroup): self {
        $this->yearGroup = $yearGroup;

        return $this;
    }

    /**
     * @return Collection<int, ClassroomHasSessions>
     */
    public function getSessions(): Collection {
        return $this->sessions;
    }

    public function addSession(ClassroomHasSessions $session): self {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setClassroom($this);
        }

        return $this;
    }

    public function removeSession(ClassroomHasSessions $session): self {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getClassroom() === $this) {
                $session->setClassroom(null);
            }
        }

        return $this;
    }

    public function addSessions(array $sessions): self {
        foreach ($sessions as $session) {
            $this->addSession($session);
        }
        return $this;
    }

    /**
     * Returns the least advanced status of a session (based on students' progression)
     * in that order:
     * - to do
     * - in progress
     * - done
     *
     * @param ClassroomHasSessions $session
     * @return string
     */
    public function getMinStatus(ClassroomHasSessions $session): string {
        $session = $session->getSession();
        $status = $this->students->map(function (Student $student) use ($session) {
            return $student->getProgressionForSession($session);
        })->map(function (StudentCompletesSessions $session) {
            return $session->getStatus();
        })->toArray();
        usort($status, function ($a, $b) {
            if ($a === 'todo') {
                return -1;
            } else if ($a === 'in_progress' && $b === 'done') {
                return -1;
            }
            return 1;
        });
        return $status[0];
    }
}
