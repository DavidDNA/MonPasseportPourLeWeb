<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
class Avatar {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'avatar', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\OneToMany(mappedBy: 'avatar', targetEntity: AvatarHasUpgrades::class, orphanRemoval: true)]
    #[Groups(['avatar'])]
    private Collection $upgrades;

    public function __construct() {
        $this->upgrades = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getStudent(): ?Student {
        return $this->student;
    }

    public function setStudent(Student $student): self {
        $this->student = $student;

        return $this;
    }

    /**
     * @return Collection<int, AvatarHasUpgrades>
     */
    public function getUpgrades(): Collection {
        return $this->upgrades;
    }

    public function addUpgrade(AvatarHasUpgrades $upgrade): self {
        if (!$this->upgrades->contains($upgrade)) {
            $this->upgrades->add($upgrade);
            $upgrade->setAvatar($this);
        }

        return $this;
    }

    public function removeUpgrade(AvatarHasUpgrades $upgrade): self {
        if ($this->upgrades->removeElement($upgrade)) {
            // set the owning side to null (unless already changed)
            if ($upgrade->getAvatar() === $this) {
                $upgrade->setAvatar(null);
            }
        }

        return $this;
    }

    public function hasUpgrade(string $name): bool {
        return $this->upgrades->exists(function ($i, AvatarHasUpgrades $u) use ($name) {
            return $u->getUpgrade()->getName() === $name;
        });
    }

    public function getUpgradesNames(): array {
        return $this->upgrades->map(function (AvatarHasUpgrades $u) {
            return $u->getUpgrade()->getName();
        })->toArray();
    }
}
