<?php

namespace App\Entity;

use App\Repository\GardenAdviceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GardenAdviceRepository::class)]
class GardenAdvice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gardenAdvice')]
    private ?Garden $garden = null;

    #[ORM\ManyToOne(inversedBy: 'gardenAdvice')]
    private ?Advice $advice = null;

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

    public function getAdvice(): ?Advice
    {
        return $this->advice;
    }

    public function setAdvice(?Advice $advice): self
    {
        $this->advice = $advice;

        return $this;
    }
}
