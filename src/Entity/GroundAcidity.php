<?php

namespace App\Entity;

use App\Repository\GroundAcidityRepository;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $high_fork = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $low_fork = null;


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

    public function getHighFork(): ?string
    {
        return $this->high_fork;
    }

    public function setHighFork(string $high_fork): self
    {
        $this->high_fork = $high_fork;

        return $this;
    }

    public function getLowFork(): ?string
    {
        return $this->low_fork;
    }

    public function setLowFork(string $low_fork): self
    {
        $this->low_fork = $low_fork;

        return $this;
    }

 
}
