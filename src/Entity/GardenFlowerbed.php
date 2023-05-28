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

    #[ORM\Column]
    private ?int $flowerbed_level = null;

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

    public function getFlowerbedLevel(): ?int
    {
        return $this->flowerbed_level;
    }

    public function setFlowerbedLevel(int $flowerbed_level): self
    {
        $this->flowerbed_level = $flowerbed_level;

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
