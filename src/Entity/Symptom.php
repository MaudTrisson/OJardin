<?php

namespace App\Entity;

use App\Repository\SymptomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SymptomRepository::class)]
class Symptom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Desease::class, mappedBy: 'symptoms')]
    private Collection $deseases;

    public function __construct()
    {
        $this->deseases = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    /**
     * @return Collection<int, Desease>
     */
    public function getDeseases(): Collection
    {
        return $this->deseases;
    }

    public function addDesease(Desease $desease): self
    {
        if (!$this->deseases->contains($desease)) {
            $this->deseases->add($desease);
            $desease->addSymptom($this);
        }

        return $this;
    }

    public function removeDesease(Desease $desease): self
    {
        if ($this->deseases->removeElement($desease)) {
            $desease->removeSymptom($this);
        }

        return $this;
    }
}
