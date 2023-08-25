<?php

namespace App\Entity;

use App\Repository\FlowerbedPlantMaintenanceActionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlowerbedPlantMaintenanceActionRepository::class)]

class FlowerbedPlantMaintenanceAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $achievementDate = null;

    #[ORM\ManyToOne(inversedBy: 'flowerbedPlantMaintenanceActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FlowerbedPlant $flowerbedPlant = null;

    #[ORM\ManyToOne(inversedBy: 'flowerbedPlantMaintenanceActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MaintenanceAction $maintenanceAction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAchievementDate(): ?\DateTimeInterface
    {
        return $this->achievementDate;
    }

    public function setAchievementDate(\DateTimeInterface $achievementDate): self
    {
        $this->achievementDate = $achievementDate;

        return $this;
    }

    public function getFlowerbedPlant(): ?FlowerbedPlant
    {
        return $this->flowerbedPlant;
    }

    public function setFlowerbedPlant(?FlowerbedPlant $flowerbedPlant): self
    {
        $this->flowerbedPlant = $flowerbedPlant;

        return $this;
    }

    public function getMaintenanceAction(): ?MaintenanceAction
    {
        return $this->maintenanceAction;
    }

    public function setMaintenanceAction(?MaintenanceAction $maintenanceAction): self
    {
        $this->maintenanceAction = $maintenanceAction;

        return $this;
    }
}
