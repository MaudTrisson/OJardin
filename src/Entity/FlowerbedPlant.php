<?php

namespace App\Entity;

use App\Repository\FlowerbedPlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlowerbedPlantRepository::class)]
class FlowerbedPlant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $planting_date = null;

    #[ORM\ManyToOne(inversedBy: 'flowerbedPlants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plant $plant = null;

    #[ORM\ManyToOne(inversedBy: 'flowerbedPlants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Flowerbed $flowerbed = null;

    #[ORM\OneToMany(mappedBy: 'flowerbedplant', targetEntity: FlowerbedPlantDesease::class)]
    private Collection $flowerbedPlantDeseases;

    #[ORM\ManyToOne(inversedBy: 'flowerbedPlants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Garden $garden = null;

    public function __construct()
    {
        $this->flowerbedPlantDeseases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlantingDate(): ?\DateTimeInterface
    {
        return $this->planting_date;
    }

    public function setPlantingDate(\DateTimeInterface $planting_date): self
    {
        $this->planting_date = $planting_date;

        return $this;
    }

    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): self
    {
        $this->plant = $plant;

        return $this;
    }

    public function getFlowerbed(): ?Flowerbed
    {
        return $this->flowerbed;
    }

    public function setFlowerbed(?Flowerbed $flowerbed): self
    {
        $this->flowerbed = $flowerbed;

        return $this;
    }

    /**
     * @return Collection<int, FlowerbedPlantDesease>
     */
    public function getFlowerbedPlantDeseases(): Collection
    {
        return $this->flowerbedPlantDeseases;
    }

    public function addFlowerbedPlantDesease(FlowerbedPlantDesease $flowerbedPlantDesease): self
    {
        if (!$this->flowerbedPlantDeseases->contains($flowerbedPlantDesease)) {
            $this->flowerbedPlantDeseases->add($flowerbedPlantDesease);
            $flowerbedPlantDesease->setFlowerbedplant($this);
        }

        return $this;
    }

    public function removeFlowerbedPlantDesease(FlowerbedPlantDesease $flowerbedPlantDesease): self
    {
        if ($this->flowerbedPlantDeseases->removeElement($flowerbedPlantDesease)) {
            // set the owning side to null (unless already changed)
            if ($flowerbedPlantDesease->getFlowerbedplant() === $this) {
                $flowerbedPlantDesease->setFlowerbedplant(null);
            }
        }

        return $this;
    }

    public function getGarden(): ?Garden
    {
        return $this->garden;
    }

    public function setGarden(?Garden $garden): self
    {
        $this->garden = $garden;

        return $this;
    }
}
