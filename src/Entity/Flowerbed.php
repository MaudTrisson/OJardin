<?php

namespace App\Entity;

use App\Repository\FlowerbedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlowerbedRepository::class)]
class Flowerbed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_upd = null;

    #[ORM\Column(length: 255)]
    private ?string $formtype = null;

    #[ORM\Column]
    private ?int $topy = null;

    #[ORM\Column]
    private ?int $leftx = null;

    #[ORM\Column]
    private ?int $width = null;

    #[ORM\Column]
    private ?int $height = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $ray = null;

    #[ORM\Column]
    private ?float $scalex = null;

    #[ORM\Column]
    private ?float $scaley = null;

    #[ORM\Column(length: 255)]
    private ?string $fill = null;

    #[ORM\Column(length: 255)]
    private ?string $stroke = null;

    #[ORM\Column]
    private ?float $flipangle = null;

    #[ORM\OneToMany(mappedBy: 'flowerbed', targetEntity: PlotPoint::class)]
    private Collection $plotpoints;

    #[ORM\ManyToOne(inversedBy: 'flowerbeds')]
    private ?GroundType $ground_type = null;

    #[ORM\ManyToOne(inversedBy: 'flowerbeds')]
    private ?GroundAcidity $ground_acidity = null;

    #[ORM\OneToMany(mappedBy: 'flowerbed', targetEntity: GardenFlowerbed::class)]
    private Collection $gardenFlowerbeds;

    #[ORM\OneToMany(mappedBy: 'flowerbed', targetEntity: FlowerbedPlant::class)]
    private Collection $flowerbedPlants;

    #[ORM\Column(nullable: true)]
    private ?float $fill_opacity = null;

    #[ORM\Column]
    private ?int $shadowtype = null;

    public function __construct()
    {
        $this->plotpoints = new ArrayCollection();
        $this->gardenFlowerbeds = new ArrayCollection();
        $this->flowerbedPlants = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(?\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;

        return $this;
    }

    public function getFormtype(): ?string
    {
        return $this->formtype;
    }

    public function setFormtype(string $formtype): self
    {
        $this->formtype = $formtype;

        return $this;
    }

    public function getTopy(): ?float
    {
        return $this->topy;
    }

    public function setTopy(float $topy): self
    {
        $this->topy = $topy;

        return $this;
    }

    public function getLeftx(): ?float
    {
        return $this->leftx;
    }

    public function setLeftx(float $leftx): self
    {
        $this->leftx = $leftx;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getRay(): ?string
    {
        return $this->ray;
    }

    public function setRay(?string $ray): self
    {
        $this->ray = $ray;

        return $this;
    }

    public function getScaley(): ?float
    {
        return $this->scaley;
    }

    public function setScaley(float $scaley): self
    {
        $this->scaley = $scaley;

        return $this;
    }

    public function getFill(): ?string
    {
        return $this->fill;
    }

    public function setFill(string $fill): self
    {
        $this->fill = $fill;

        return $this;
    }

    public function getScalex(): ?float
    {
        return $this->scalex;
    }

    public function setScalex(float $scalex): self
    {
        $this->scalex = $scalex;

        return $this;
    }

    public function getFillOpacity(): ?float
    {
        return $this->fill_opacity;
    }

    public function setFillOpacity(?float $fill_opacity): self
    {
        $this->fill_opacity = $fill_opacity;

        return $this;
    }

    public function getStroke(): ?string
    {
        return $this->stroke;
    }

    public function setStroke(string $stroke): self
    {
        $this->stroke = $stroke;

        return $this;
    }

    public function getFlipangle(): ?float
    {
        return $this->flipangle;
    }

    public function setFlipangle(float $flipangle): self
    {
        $this->flipangle = $flipangle;

        return $this;
    }

    /**
     * @return Collection<int, PlotPoint>
     */
    public function getPlotpoints(): Collection
    {
        return $this->plotpoints;
    }

    public function addPlotpoint(PlotPoint $plotpoint): self
    {
        if (!$this->plotpoints->contains($plotpoint)) {
            $this->plotpoints->add($plotpoint);
            $plotpoint->setFlowerbed($this);
        }

        return $this;
    }

    public function removePlotpoint(PlotPoint $plotpoint): self
    {
        if ($this->plotpoints->removeElement($plotpoint)) {
            // set the owning side to null (unless already changed)
            if ($plotpoint->getFlowerbed() === $this) {
                $plotpoint->setFlowerbed(null);
            }
        }

        return $this;
    }

    public function getGroundType(): ?GroundType
    {
        return $this->ground_type;
    }

    public function setGroundType(?GroundType $ground_type): self
    {
        $this->ground_type = $ground_type;

        return $this;
    }

    public function getGroundAcidity(): ?GroundAcidity
    {
        return $this->ground_acidity;
    }

    public function setGroundAcidity(?GroundAcidity $ground_acidity): self
    {
        $this->ground_acidity = $ground_acidity;

        return $this;
    }

    /**
     * @return Collection<int, GardenFlowerbed>
     */
    public function getGardenFlowerbeds(): Collection
    {
        return $this->gardenFlowerbeds;
    }

    public function addGardenFlowerbed(GardenFlowerbed $gardenFlowerbed): self
    {
        if (!$this->gardenFlowerbeds->contains($gardenFlowerbed)) {
            $this->gardenFlowerbeds->add($gardenFlowerbed);
            $gardenFlowerbed->setFlowerbed($this);
        }

        return $this;
    }

    public function removeGardenFlowerbed(GardenFlowerbed $gardenFlowerbed): self
    {
        if ($this->gardenFlowerbeds->removeElement($gardenFlowerbed)) {
            // set the owning side to null (unless already changed)
            if ($gardenFlowerbed->getFlowerbed() === $this) {
                $gardenFlowerbed->setFlowerbed(null);
            }
        }

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
            $flowerbedPlant->setFlowerbed($this);
        }

        return $this;
    }

    public function removeFlowerbedPlant(FlowerbedPlant $flowerbedPlant): self
    {
        if ($this->flowerbedPlants->removeElement($flowerbedPlant)) {
            // set the owning side to null (unless already changed)
            if ($flowerbedPlant->getFlowerbed() === $this) {
                $flowerbedPlant->setFlowerbed(null);
            }
        }

        return $this;
    }

    public function getShadowtype(): ?int
    {
        return $this->shadowtype;
    }

    public function setShadowtype(int $shadowtype): self
    {
        $this->shadowtype = $shadowtype;

        return $this;
    }

}
