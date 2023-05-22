<?php

namespace App\Entity;

use App\Repository\GroundAcidityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroundAcidityRepository::class)]
class GroundAcidity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $high_fork = null;

    #[ORM\Column]
    private ?float $low_fork = null;

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

    public function getHighFork(): ?float
    {
        return $this->high_fork;
    }

    public function setHighFork(float $high_fork): self
    {
        $this->high_fork = $high_fork;

        return $this;
    }

    public function getLowFork(): ?float
    {
        return $this->low_fork;
    }

    public function setLowFork(float $low_fork): self
    {
        $this->low_fork = $low_fork;

        return $this;
    }
}
