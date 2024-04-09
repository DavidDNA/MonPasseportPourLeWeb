<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $studentsResources = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $PER = [];

    #[ORM\Column(length: 255)]
    private ?string $PERMainColor = null;

    #[ORM\Column(length: 255)]
    private ?string $PERTextColor = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $PERObjectives = null;

    #[ORM\ManyToOne]
    private ?YearGroup $yearGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objectives = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $material = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $teacherIndications = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $studentResources = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: SessionActivity::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $activities;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $materialUrl = null;

    public function __construct() {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getStudentsResources(): ?string {
        return $this->studentsResources;
    }

    public function setStudentsResources(?string $studentsResources): self {
        $this->studentsResources = $studentsResources;

        return $this;
    }

    public function getPER(): array|null {
        return $this->PER;
    }

    public function setPER(?array $PER): self {
        $this->PER = $PER;

        return $this;
    }

    public function getPERMainColor(): ?string {
        return $this->PERMainColor;
    }

    public function setPERMainColor(string $PERMainColor): self {
        $this->PERMainColor = $PERMainColor;

        return $this;
    }

    public function getPERTextColor(): ?string {
        return $this->PERTextColor;
    }

    public function setPERTextColor(string $PERTextColor): self {
        $this->PERTextColor = $PERTextColor;

        return $this;
    }

    public function getPERObjectives(): ?string {
        return $this->PERObjectives;
    }

    public function setPERObjectives(?string $PERObjectives): self {
        $this->PERObjectives = $PERObjectives;

        return $this;
    }

    public function getYearGroup(): ?YearGroup {
        return $this->yearGroup;
    }

    public function setYearGroup(?YearGroup $yearGroup): self {
        $this->yearGroup = $yearGroup;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getObjectives(): ?string {
        return $this->objectives;
    }

    public function setObjectives(?string $objectives): self {
        $this->objectives = $objectives;

        return $this;
    }

    public function getMaterial(): ?string {
        return $this->material;
    }

    public function setMaterial(?string $material): self {
        $this->material = $material;

        return $this;
    }

    public function getTeacherIndications(): ?string {
        return $this->teacherIndications;
    }

    public function setTeacherIndications(?string $teacherIndications): self {
        $this->teacherIndications = $teacherIndications;

        return $this;
    }

    public function getSummary(): ?string {
        return $this->summary;
    }

    public function setSummary(?string $summary): self {
        $this->summary = $summary;

        return $this;
    }

    public function setStudentResources(?string $studentResources): Session {
        $this->studentResources = $studentResources;
        return $this;
    }

    public function getStudentResources(): ?string {
        return $this->studentResources;
    }

    public function getActivities(): Collection {
        return $this->activities;
    }

    public function addActivity(SessionActivity $activity): self {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setSession($this);
        }

        return $this;
    }

    public function removeActivity(SessionActivity $activity): self {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getSession() === $this) {
                $activity->setSession(null);
            }
        }

        return $this;
    }

    public function getMaterialUrl(): ?string {
        return $this->materialUrl;
    }

    public function setMaterialUrl(?string $materialUrl): static {
        $this->materialUrl = $materialUrl;

        return $this;
    }
}
