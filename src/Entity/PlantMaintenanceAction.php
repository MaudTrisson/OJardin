<?php

namespace App\Entity;

use App\Repository\PlantMaintenanceActionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantMaintenanceActionRepository::class)]
class PlantMaintenanceAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $frequencyDays = null;

    #[ORM\ManyToOne(inversedBy: 'plantMaintenanceActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plant $plant = null;

    #[ORM\ManyToOne(inversedBy: 'plantMaintenanceActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MaintenanceAction $maintenance_action = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrequencyDays(): ?int
    {
        return $this->frequencyDays;
    }

    public function setFrequencyDays(int $frequencyDays): self
    {
        $this->frequencyDays = $frequencyDays;

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

    public function getMaintenanceAction(): ?MaintenanceAction
    {
        return $this->maintenance_action;
    }

    public function setMaintenanceAction(?MaintenanceAction $maintenance_action): self
    {
        $this->maintenance_action = $maintenance_action;

        return $this;
    }
}
