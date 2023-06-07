<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GardenUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: GardenUserRepository::class)]
class GardenUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isOwner = null;

    #[ORM\ManyToOne(inversedBy: 'gardenUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'gardenUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Garden $garden = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsOwner(): ?bool
    {
        return $this->isOwner;
    }

    public function setIsOwner(bool $isOwner): self
    {
        $this->isOwner = $isOwner;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGarden(): ?Garden
    {
        return $this->garden;
    }

    public function setGarden(?Garden $garden): self
    {
        $this->garden = $garden;

        return $this;
    }

}
