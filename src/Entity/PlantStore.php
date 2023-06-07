<?php

namespace App\Entity;

use App\Repository\PlantStoreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantStoreRepository::class)]
class PlantStore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $qty_in_stock = null;

    #[ORM\ManyToOne(inversedBy: 'plantStores')]
    private ?Plant $plant = null;

    #[ORM\ManyToOne(inversedBy: 'plantStores')]
    private ?Store $store = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
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

    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): self
    {
        $this->plant = $plant;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }
}
