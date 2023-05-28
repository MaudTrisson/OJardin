<?php

namespace App\Entity;

use App\Repository\GroundTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroundTypeRepository::class)]
class GroundType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'ground_type', targetEntity: Flowerbed::class)]
    private Collection $flowerbeds;

    #[ORM\ManyToMany(targetEntity: Plant::class, mappedBy: 'ground_types')]
    private Collection $plants;

    #[ORM\ManyToMany(targetEntity: Advice::class, mappedBy: 'ground_types')]
    private Collection $advice;

    public function __construct()
    {
        $this->flowerbeds = new ArrayCollection();
        $this->plants = new ArrayCollection();
        $this->advice = new ArrayCollection();
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
            $flowerbed->setGroundType($this);
        }

        return $this;
    }

    public function removeFlowerbed(Flowerbed $flowerbed): self
    {
        if ($this->flowerbeds->removeElement($flowerbed)) {
            // set the owning side to null (unless already changed)
            if ($flowerbed->getGroundType() === $this) {
                $flowerbed->setGroundType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plant>
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }

    public function addPlant(Plant $plant): self
    {
        if (!$this->plants->contains($plant)) {
            $this->plants->add($plant);
            $plant->addGroundType($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        if ($this->plants->removeElement($plant)) {
            $plant->removeGroundType($this);
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
            $advice->addGroundType($this);
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): self
    {
        if ($this->advice->removeElement($advice)) {
            $advice->removeGroundType($this);
        }

        return $this;
    }
}
