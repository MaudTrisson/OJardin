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

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $qty_in_stock = null;

    #[ORM\ManyToMany(targetEntity: Plant::class, mappedBy: 'stores')]
    private Collection $plants;

    #[ORM\OneToMany(mappedBy: 'store', targetEntity: StoreWeekDay::class)]
    private Collection $storeWeekDays;

    public function __construct()
    {
        $this->plants = new ArrayCollection();
        $this->storeWeekDays = new ArrayCollection();
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQtyInStock(): ?int
    {
        return $this->qty_in_stock;
    }

    public function setQtyInStock(int $qty_in_stock): self
    {
        $this->qty_in_stock = $qty_in_stock;

        return $this;
    }

    /**
     * @return Collection<int, Plant>
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }

    public function addPlant(Plant $plant): self
    {
        if (!$this->plants->contains($plant)) {
            $this->plants->add($plant);
            $plant->addStore($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        if ($this->plants->removeElement($plant)) {
            $plant->removeStore($this);
        }

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

}
