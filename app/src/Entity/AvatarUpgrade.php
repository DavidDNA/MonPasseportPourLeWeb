<?php

namespace App\Entity;

use App\Repository\AvatarUpgradeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AvatarUpgradeRepository::class)]
class AvatarUpgrade {

    const DefaultName = "default";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['avatar'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $choices = [];

    #[ORM\Column]
    #[Groups(['avatar'])]
    private int $priority = 0;

    #[ORM\Column(length: 255)]
    #[Groups(['avatar'])]
    private ?string $type = null;

    private ?float $relativeThreshold = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function IsDefault(): bool {
        return $this->name === self::DefaultName;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getChoices(): array {
        return $this->choices;
    }

    public function setChoices(array $choices): self {
        $this->choices = $choices;
        return $this;
    }

    public function getRelativeThreshold(): ?float {
        return $this->relativeThreshold;
    }

    public function setRelativeThreshold(float $relativeThreshold): self {
        $this->relativeThreshold = $relativeThreshold;
        return $this;
    }

    public function getPriority(): int {
        return $this->priority;
    }

    public function setPriority(int $priority): self {
        $this->priority = $priority;

        return $this;
    }

    public function getType(): ?string {
        return $this->type;
    }

    public function setType(string $type): self {
        $this->type = $type;

        return $this;
    }
}
