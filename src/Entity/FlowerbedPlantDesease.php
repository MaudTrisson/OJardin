<?php

namespace App\Entity;

use App\Repository\FlowerbedPlantDeseaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlowerbedPlantDeseaseRepository::class)]
class FlowerbedPlantDesease
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'flowerbedPlantDeseases')]
    private ?FlowerbedPlant $flowerbedplant = null;

    #[ORM\ManyToOne(inversedBy: 'flowerbedPlantDeseases')]
    private ?Desease $desease = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlowerbedplant(): ?FlowerbedPlant
    {
        return $this->flowerbedplant;
    }

    public function setFlowerbedplant(?FlowerbedPlant $flowerbedplant): self
    {
        $this->flowerbedplant = $flowerbedplant;

        return $this;
    }

    public function getDesease(): ?Desease
    {
        return $this->desease;
    }

    public function setDesease(?Desease $desease): self
    {
        $this->desease = $desease;

        return $this;
    }
}
