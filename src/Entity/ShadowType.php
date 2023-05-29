<?php

namespace App\Entity;

use App\Repository\ShadowTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShadowTypeRepository::class)]
class ShadowType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'shadow_type', targetEntity: Flowerbed::class)]
    private Collection $flowerbeds;

    #[ORM\ManyToMany(targetEntity: Advice::class, mappedBy: 'shadow_types')]
    private Collection $advice;

    public function __construct()
    {
        $this->flowerbeds = new ArrayCollection();
        $this->advice = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Flowerbed>
     */
    public function getFlowerbeds(): Collection
    {
        return $this->flowerbeds;
    }

    public function addFlowerbed(Flowerbed $flowerbed): self
    {
        if (!$this->flowerbeds->contains($flowerbed)) {
            $this->flowerbeds->add($flowerbed);
            $flowerbed->setShadowType($this);
        }

        return $this;
    }

    public function removeFlowerbed(Flowerbed $flowerbed): self
    {
        if ($this->flowerbeds->removeElement($flowerbed)) {
            // set the owning side to null (unless already changed)
            if ($flowerbed->getShadowType() === $this) {
                $flowerbed->setShadowType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Advice>
     */
    public function getAdvice(): Collection
    {
        return $this->advice;
    }

    public function addAdvice(Advice $advice): self
    {
        if (!$this->advice->contains($advice)) {
            $this->advice->add($advice);
            $advice->addShadowType($this);
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): self
    {
        if ($this->advice->removeElement($advice)) {
            $advice->removeShadowType($this);
        }

        return $this;
    }
}
