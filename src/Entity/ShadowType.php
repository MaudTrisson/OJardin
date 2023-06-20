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

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?float $color_opacity = null;


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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColorOpacity(): ?float
    {
        return $this->color_opacity;
    }

    public function setColorOpacity(?float $color_opacity): self
    {
        $this->color_opacity = $color_opacity;

        return $this;
    }

}
