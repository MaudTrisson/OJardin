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

    public function __construct()
    {
        $this->plantMaintenanceActions = new ArrayCollection();
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

}
