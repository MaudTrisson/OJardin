<?php

namespace App\Entity;

use App\Repository\GroundAcidityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroundAcidityRepository::class)]
class GroundAcidity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $high_fork = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $low_fork = null;

    #[ORM\OneToMany(mappedBy: 'ground_acidity', targetEntity: Flowerbed::class)]
    private Collection $flowerbeds;

    #[ORM\ManyToMany(targetEntity: Plant::class, mappedBy: 'ground_acidities')]
    private Collection $plants;

    #[ORM\ManyToMany(targetEntity: Advice::class, mappedBy: 'ground_acidities')]
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

    public function getHighFork(): ?string
    {
        return $this->high_fork;
    }

    public function setHighFork(string $high_fork): self
    {
        $this->high_fork = $high_fork;

        return $this;
    }

    public function getLowFork(): ?string
    {
        return $this->low_fork;
    }

    public function setLowFork(string $low_fork): self
    {
        $this->low_fork = $low_fork;

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
            $flowerbed->setGroundAcidity($this);
        }

        return $this;
    }

    public function removeFlowerbed(Flowerbed $flowerbed): self
    {
        if ($this->flowerbeds->removeElement($flowerbed)) {
            // set the owning side to null (unless already changed)
            if ($flowerbed->getGroundAcidity() === $this) {
                $flowerbed->setGroundAcidity(null);
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
            $plant->addGroundAcidity($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        if ($this->plants->removeElement($plant)) {
            $plant->removeGroundAcidity($this);
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
            $advice->addGroundAcidity($this);
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): self
    {
        if ($this->advice->removeElement($advice)) {
            $advice->removeGroundAcidity($this);
        }

        return $this;
    }

 
}
