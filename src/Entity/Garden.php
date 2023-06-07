<?php

namespace App\Entity;

use App\Repository\GardenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GardenRepository::class)]
class Garden
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 5)]
    private ?string $postalcode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_upd = null;

    #[ORM\Column]
    private ?bool $compost = null;

    #[ORM\Column(nullable: true)]
    private ?int $water_collector_qty = null;

    #[ORM\ManyToOne(inversedBy: 'gardens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $regions = null;

    #[ORM\OneToMany(mappedBy: 'garden', targetEntity: GardenUser::class)]
    private Collection $gardenUsers;

    #[ORM\OneToMany(mappedBy: 'garden', targetEntity: GardenFlowerbed::class)]
    private Collection $gardenFlowerbeds;

    #[ORM\OneToMany(mappedBy: 'garden', targetEntity: GardenAdvice::class)]
    private Collection $gardenAdvice;

    public function __construct()
    {
        $this->gardenUsers = new ArrayCollection();
        $this->gardenFlowerbeds = new ArrayCollection();
        $this->gardenAdvice = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;

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

    public function getWaterCollectorQty(): ?int
    {
        return $this->water_collector_qty;
    }

    public function setWaterCollectorQty(?int $water_collector_qty): self
    {
        $this->water_collector_qty = $water_collector_qty;

        return $this;
    }

    public function getRegions(): ?Region
    {
        return $this->regions;
    }

    public function setRegions(?Region $regions): self
    {
        $this->regions = $regions;

        return $this;
    }

    /**
     * @return Collection<int, GardenUser>
     */
    public function getGardenUsers(): Collection
    {
        return $this->gardenUsers;
    }

    public function addGardenUser(GardenUser $gardenUser): self
    {
        
        if (!$this->gardenUsers->contains($gardenUser)) {
            
            $this->gardenUsers->add($gardenUser);
            $gardenUser->setGarden($this);
        }

        return $this;
    }

    public function removeGardenUser(GardenUser $gardenUser): self
    {
        if ($this->gardenUsers->removeElement($gardenUser)) {
            // set the owning side to null (unless already changed)
            if ($gardenUser->getGarden() === $this) {
                $gardenUser->setGarden(null);
            }
        }

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
            $gardenFlowerbed->setGarden($this);
        }

        return $this;
    }

    public function removeGardenFlowerbed(GardenFlowerbed $gardenFlowerbed): self
    {
        if ($this->gardenFlowerbeds->removeElement($gardenFlowerbed)) {
            // set the owning side to null (unless already changed)
            if ($gardenFlowerbed->getGarden() === $this) {
                $gardenFlowerbed->setGarden(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GardenAdvice>
     */
    public function getGardenAdvice(): Collection
    {
        return $this->gardenAdvice;
    }

    public function addGardenAdvice(GardenAdvice $gardenAdvice): self
    {
        if (!$this->gardenAdvice->contains($gardenAdvice)) {
            $this->gardenAdvice->add($gardenAdvice);
            $gardenAdvice->setGarden($this);
        }

        return $this;
    }

    public function removeGardenAdvice(GardenAdvice $gardenAdvice): self
    {
        if ($this->gardenAdvice->removeElement($gardenAdvice)) {
            // set the owning side to null (unless already changed)
            if ($gardenAdvice->getGarden() === $this) {
                $gardenAdvice->setGarden(null);
            }
        }

        return $this;
    }

}
