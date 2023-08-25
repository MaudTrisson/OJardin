<?php

namespace App\Entity;

use App\Repository\MaintenanceActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaintenanceActionRepository::class)]
class MaintenanceAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'maintenance_action', targetEntity: PlantMaintenanceAction::class)]
    private Collection $plantMaintenanceActions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'maintenanceAction', targetEntity: FlowerbedPlantMaintenanceAction::class, orphanRemoval: true)]
    private Collection $flowerbedPlantMaintenanceActions;

    public function __construct()
    {
        $this->plantMaintenanceActions = new ArrayCollection();
        $this->flowerbedPlantMaintenanceActions = new ArrayCollection();
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

    /**
     * @return Collection<int, PlantMaintenanceAction>
     */
    public function getPlantMaintenanceActions(): Collection
    {
        return $this->plantMaintenanceActions;
    }

    public function addPlantMaintenanceAction(PlantMaintenanceAction $plantMaintenanceAction): self
    {
        if (!$this->plantMaintenanceActions->contains($plantMaintenanceAction)) {
            $this->plantMaintenanceActions->add($plantMaintenanceAction);
            $plantMaintenanceAction->setMaintenanceAction($this);
        }

        return $this;
    }

    public function removePlantMaintenanceAction(PlantMaintenanceAction $plantMaintenanceAction): self
    {
        if ($this->plantMaintenanceActions->removeElement($plantMaintenanceAction)) {
            // set the owning side to null (unless already changed)
            if ($plantMaintenanceAction->getMaintenanceAction() === $this) {
                $plantMaintenanceAction->setMaintenanceAction(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, FlowerbedPlantMaintenanceAction>
     */
    public function getFlowerbedPlantMaintenanceActions(): Collection
    {
        return $this->flowerbedPlantMaintenanceActions;
    }

    public function addFlowerbedPlantMaintenanceAction(FlowerbedPlantMaintenanceAction $flowerbedPlantMaintenanceAction): self
    {
        if (!$this->flowerbedPlantMaintenanceActions->contains($flowerbedPlantMaintenanceAction)) {
            $this->flowerbedPlantMaintenanceActions->add($flowerbedPlantMaintenanceAction);
            $flowerbedPlantMaintenanceAction->setMaintenanceAction($this);
        }

        return $this;
    }

    public function removeFlowerbedPlantMaintenanceAction(FlowerbedPlantMaintenanceAction $flowerbedPlantMaintenanceAction): self
    {
        if ($this->flowerbedPlantMaintenanceActions->removeElement($flowerbedPlantMaintenanceAction)) {
            // set the owning side to null (unless already changed)
            if ($flowerbedPlantMaintenanceAction->getMaintenanceAction() === $this) {
                $flowerbedPlantMaintenanceAction->setMaintenanceAction(null);
            }
        }

        return $this;
    }

}
