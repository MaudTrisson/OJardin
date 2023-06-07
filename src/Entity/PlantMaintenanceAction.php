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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $achievement = null;

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

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getAchievement(): ?\DateTimeInterface
    {
        return $this->achievement;
    }

    public function setAchievement(\DateTimeInterface $achievement): self
    {
        $this->achievement = $achievement;

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
