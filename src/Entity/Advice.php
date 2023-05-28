<?php

namespace App\Entity;

use App\Repository\AdviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdviceRepository::class)]
class Advice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $compost = null;

    #[ORM\Column]
    private ?bool $water_collector = null;

    #[ORM\Column]
    private ?int $rainfall_rate_need = null;

    #[ORM\Column]
    private ?int $sunshine_rate_need = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $garden_size = null;

    #[ORM\ManyToMany(targetEntity: Garden::class, mappedBy: 'advices')]
    private Collection $gardens;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'advices')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Usefulness::class, inversedBy: 'advice')]
    private Collection $usefulnesses;

    #[ORM\ManyToMany(targetEntity: GroundAcidity::class, inversedBy: 'advice')]
    private Collection $ground_acidities;

    #[ORM\ManyToMany(targetEntity: GroundType::class, inversedBy: 'advice')]
    private Collection $ground_types;

    #[ORM\ManyToMany(targetEntity: ShadowType::class, inversedBy: 'advice')]
    private Collection $shadow_types;

    #[ORM\ManyToMany(targetEntity: Region::class, inversedBy: 'advice')]
    private Collection $regions;

    public function __construct()
    {
        $this->gardens = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->usefulnesses = new ArrayCollection();
        $this->ground_acidities = new ArrayCollection();
        $this->ground_types = new ArrayCollection();
        $this->shadow_types = new ArrayCollection();
        $this->regions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isCompost(): ?bool
    {
        return $this->compost;
    }

    public function setCompost(bool $compost): self
    {
        $this->compost = $compost;

        return $this;
    }

    public function isWaterCollector(): ?bool
    {
        return $this->water_collector;
    }

    public function setWaterCollector(bool $water_collector): self
    {
        $this->water_collector = $water_collector;

        return $this;
    }

    public function getRainfallRateNeed(): ?int
    {
        return $this->rainfall_rate_need;
    }

    public function setRainfallRateNeed(int $rainfall_rate_need): self
    {
        $this->rainfall_rate_need = $rainfall_rate_need;

        return $this;
    }

    public function getSunshineRateNeed(): ?int
    {
        return $this->sunshine_rate_need;
    }

    public function setSunshineRateNeed(int $sunshine_rate_need): self
    {
        $this->sunshine_rate_need = $sunshine_rate_need;

        return $this;
    }

    public function getGardenSize(): ?string
    {
        return $this->garden_size;
    }

    public function setGardenSize(?string $garden_size): self
    {
        $this->garden_size = $garden_size;

        return $this;
    }

    /**
     * @return Collection<int, Garden>
     */
    public function getGardens(): Collection
    {
        return $this->gardens;
    }

    public function addGarden(Garden $garden): self
    {
        if (!$this->gardens->contains($garden)) {
            $this->gardens->add($garden);
            $garden->addAdvice($this);
        }

        return $this;
    }

    public function removeGarden(Garden $garden): self
    {
        if ($this->gardens->removeElement($garden)) {
            $garden->removeAdvice($this);
        }

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
            $category->addAdvice($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeAdvice($this);
        }

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
     * @return Collection<int, ShadowType>
     */
    public function getShadowTypes(): Collection
    {
        return $this->shadow_types;
    }

    public function addShadowType(ShadowType $shadowType): self
    {
        if (!$this->shadow_types->contains($shadowType)) {
            $this->shadow_types->add($shadowType);
        }

        return $this;
    }

    public function removeShadowType(ShadowType $shadowType): self
    {
        $this->shadow_types->removeElement($shadowType);

        return $this;
    }

    /**
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions->add($region);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        $this->regions->removeElement($region);

        return $this;
    }

}
