<?php

namespace App\Entity;

use App\Repository\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoreRepository::class)]
class Store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: StoreWeekDay::class)]
    private Collection $storeWeekDays;

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: PlantStore::class)]
    private Collection $plantStores;

    #[ORM\Column(length: 5)]
    private ?string $postalcode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    public function __construct()
    {
        $this->storeWeekDays = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, StoreWeekDay>
     */
    public function getStoreWeekDays(): Collection
    {
        return $this->storeWeekDays;
    }

    public function addStoreWeekDay(StoreWeekDay $storeWeekDay): self
    {
        if (!$this->storeWeekDays->contains($storeWeekDay)) {
            $this->storeWeekDays->add($storeWeekDay);
            $storeWeekDay->setStore($this);
        }

        return $this;
    }

    public function removeStoreWeekDay(StoreWeekDay $storeWeekDay): self
    {
        if ($this->storeWeekDays->removeElement($storeWeekDay)) {
            // set the owning side to null (unless already changed)
            if ($storeWeekDay->getStore() === $this) {
                $storeWeekDay->setStore(null);
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
            $plantStore->setStore($this);
        }

        return $this;
    }

    public function removePlantStore(PlantStore $plantStore): self
    {
        if ($this->plantStores->removeElement($plantStore)) {
            // set the owning side to null (unless already changed)
            if ($plantStore->getStore() === $this) {
                $plantStore->setStore(null);
            }
        }

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

}
