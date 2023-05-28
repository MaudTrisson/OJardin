<?php

namespace App\Entity;

use App\Repository\FlowerbedPlantRepository;
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
}
