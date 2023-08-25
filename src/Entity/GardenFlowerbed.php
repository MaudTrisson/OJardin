<?php

namespace App\Entity;

use App\Repository\GardenFlowerbedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GardenFlowerbedRepository::class)]
class GardenFlowerbed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gardenFlowerbeds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Garden $garden = null;

    #[ORM\ManyToOne(inversedBy: 'gardenFlowerbeds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Flowerbed $flowerbed = null;

    public function getId(): ?int
    {
        return $this->id;
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
