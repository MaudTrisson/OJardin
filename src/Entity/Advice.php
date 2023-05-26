<?php

namespace App\Entity;

use App\Repository\AdviceRepository;
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

}
