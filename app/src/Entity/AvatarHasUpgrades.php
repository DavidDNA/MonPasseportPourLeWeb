<?php

namespace App\Entity;

use App\Repository\AvatarHasUpgradesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AvatarHasUpgradesRepository::class)]
class AvatarHasUpgrades {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['avatar'])]
    private ?string $choice = null;

    #[ORM\ManyToOne(inversedBy: 'upgrades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Avatar $avatar = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['avatar'])]
    private ?AvatarUpgrade $upgrade = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getChoice(): ?string {
        return $this->choice;
    }

    public function setChoice(string $choice): self {
        $this->choice = $choice;

        return $this;
    }

    public function getAvatar(): ?Avatar {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar): self {
        $this->avatar = $avatar;

        return $this;
    }

    public function getUpgrade(): ?AvatarUpgrade {
        return $this->upgrade;
    }

    public function setUpgrade(?AvatarUpgrade $upgrade): self {
        $this->upgrade = $upgrade;

        return $this;
    }
}
