<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantRepository::class)]
class Plant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $lifetime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $recommending_planting_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $flowering_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $flowering_end = null;

    #[ORM\Column]
    private ?bool $leaves_persistence = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $height = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $width = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $rainfall_rate_need = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $sunshine_rate_need = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2, nullable: true)]
    private ?string $freeze_sensibility_max = null;

    #[ORM\ManyToOne(inversedBy: 'plants')]
    private ?Color $color = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'plants')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Usefulness::class, inversedBy: 'plants')]
    private Collection $usefulnesses;

    #[ORM\ManyToMany(targetEntity: GroundAcidity::class, inversedBy: 'plants')]
    private Collection $ground_acidities;

    #[ORM\ManyToMany(targetEntity: GroundType::class, inversedBy: 'plants')]
    private Collection $ground_types;

    #[ORM\OneToMany(mappedBy: 'plant', targetEntity: FlowerbedPlant::class)]
    private Collection $flowerbedPlants;

    #[ORM\OneToMany(mappedBy: 'plant', targetEntity: PlantMaintenanceAction::class)]
    private Collection $plantMaintenanceActions;

    #[ORM\OneToMany(mappedBy: 'plant', targetEntity: PlantStore::class)]
    private Collection $plantStores;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->usefulnesses = new ArrayCollection();
        $this->ground_acidities = new ArrayCollection();
        $this->ground_types = new ArrayCollection();
        $this->flowerbedPlants = new ArrayCollection();
        $this->plantMaintenanceActions = new ArrayCollection();
        $this->plantStores = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLifetime(): ?int
    {
        return $this->lifetime;
    }

    public function setLifetime(int $lifetime): self
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    public function getRecommendingPlantingDate(): ?\DateTimeInterface
    {
        return $this->recommending_planting_date;
    }

    public function setRecommendingPlantingDate(\DateTimeInterface $recommending_planting_date): self
    {
        $this->recommending_planting_date = $recommending_planting_date;

        return $this;
    }

    public function getFloweringStart(): ?\DateTimeInterface
    {
        return $this->flowering_start;
    }

    public function setFloweringStart(\DateTimeInterface $flowering_start): self
    {
        $this->flowering_start = $flowering_start;

        return $this;
    }

    public function getFloweringEnd(): ?\DateTimeInterface
    {
        return $this->flowering_end;
    }

    public function setFloweringEnd(\DateTimeInterface $flowering_end): self
    {
        $this->flowering_end = $flowering_end;

        return $this;
    }

    public function isLeavesPersistence(): ?bool
    {
        return $this->leaves_persistence;
    }

    public function setLeavesPersistence(bool $leaves_persistence): self
    {
        $this->leaves_persistence = $leaves_persistence;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getRainfallRateNeed(): ?string
    {
        return $this->rainfall_rate_need;
    }

    public function setRainfallRateNeed(string $rainfall_rate_need): self
    {
        $this->rainfall_rate_need = $rainfall_rate_need;

        return $this;
    }

    public function getSunshineRateNeed(): ?string
    {
        return $this->sunshine_rate_need;
    }

    public function setSunshineRateNeed(string $sunshine_rate_need): self
    {
        $this->sunshine_rate_need = $sunshine_rate_need;

        return $this;
    }

    public function getFreezeSensibilityMax(): ?string
    {
        return $this->freeze_sensibility_max;
    }

    public function setFreezeSensibilityMax(?string $freeze_sensibility_max): self
    {
        $this->freeze_sensibility_max = $freeze_sensibility_max;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Usefulness>
     */
    public function getUsefulnesses(): Collection
    {
        return $this->usefulnesses;
    }

    public function addUsefulness(Usefulness $usefulness): self
    {
        if (!$this->usefulnesses->contains($usefulness)) {
            $this->usefulnesses->add($usefulness);
        }

        return $this;
    }

    public function removeUsefulness(Usefulness $usefulness): self
    {
        $this->usefulnesses->removeElement($usefulness);

        return $this;
    }

    /**
     * @return Collection<int, GroundAcidity>
     */
    public function getGroundAcidities(): Collection
    {
        return $this->ground_acidities;
    }

    public function addGroundAcidity(GroundAcidity $groundAcidity): self
    {
        if (!$this->ground_acidities->contains($groundAcidity)) {
            $this->ground_acidities->add($groundAcidity);
        }

        return $this;
    }

    public function removeGroundAcidity(GroundAcidity $groundAcidity): self
    {
        $this->ground_acidities->removeElement($groundAcidity);

        return $this;
    }

    /**
     * @return Collection<int, GroundType>
     */
    public function getGroundTypes(): Collection
    {
        return $this->ground_types;
    }

    public function addGroundType(GroundType $groundType): self
    {
        if (!$this->ground_types->contains($groundType)) {
            $this->ground_types->add($groundType);
        }

        return $this;
    }

    public function removeGroundType(GroundType $groundType): self
    {
        $this->ground_types->removeElement($groundType);

        return $this;
    }

    /**
     * @return Collection<int, FlowerbedPlant>
     */
    public function getFlowerbedPlants(): Collection
    {
        return $this->flowerbedPlants;
    }

    public function addFlowerbedPlant(FlowerbedPlant $flowerbedPlant): self
    {
        if (!$this->flowerbedPlants->contains($flowerbedPlant)) {
            $this->flowerbedPlants->add($flowerbedPlant);
            $flowerbedPlant->setPlant($this);
        }

        return $this;
    }

    public function removeFlowerbedPlant(FlowerbedPlant $flowerbedPlant): self
    {
        if ($this->flowerbedPlants->removeElement($flowerbedPlant)) {
            // set the owning side to null (unless already changed)
            if ($flowerbedPlant->getPlant() === $this) {
                $flowerbedPlant->setPlant(null);
            }
        }

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
            $plantMaintenanceAction->setPlant($this);
        }

        return $this;
    }

    public function removePlantMaintenanceAction(PlantMaintenanceAction $plantMaintenanceAction): self
    {
        if ($this->plantMaintenanceActions->removeElement($plantMaintenanceAction)) {
            // set the owning side to null (unless already changed)
            if ($plantMaintenanceAction->getPlant() === $this) {
                $plantMaintenanceAction->setPlant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlantStore>
     */
    public function getPlantStores(): Collection
    {
        return $this->plantStores;
    }

    public function addPlantStore(PlantStore $plantStore): self
    {
        if (!$this->plantStores->contains($plantStore)) {
            $this->plantStores->add($plantStore);
            $plantStore->setPlant($this);
        }

        return $this;
    }

    public function removePlantStore(PlantStore $plantStore): self
    {
        if ($this->plantStores->removeElement($plantStore)) {
            // set the owning side to null (unless already changed)
            if ($plantStore->getPlant() === $this) {
                $plantStore->setPlant(null);
            }
        }

        return $this;
    }

}
