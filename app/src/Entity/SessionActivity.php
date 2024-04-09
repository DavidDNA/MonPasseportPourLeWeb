<?php

namespace App\Entity;

use App\Repository\SessionActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionActivityRepository::class)]
class SessionActivity {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $duration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resources = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Session $session = null;

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

    public function getDuration(): ?string {
        return $this->duration;
    }

    public function setDuration(?string $duration): self {
        $this->duration = $duration;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(?string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getResources(): ?string {
        return $this->resources;
    }

    public function setResources(?string $resources): self {
        $this->resources = $resources;

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
