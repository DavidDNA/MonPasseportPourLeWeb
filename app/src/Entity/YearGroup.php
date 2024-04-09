<?php

namespace App\Entity;

use App\Repository\YearGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YearGroupRepository::class)]
class YearGroup {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column]
    private ?int $avatarUpgrades = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function isEnabled(): ?bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }

    public function getAvatarUpgrades(): ?int
    {
        return $this->avatarUpgrades;
    }

    public function setAvatarUpgrades(int $avatarUpgrades): self
    {
        $this->avatarUpgrades = $avatarUpgrades;

        return $this;
    }
}
