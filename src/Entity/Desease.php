<?php

namespace App\Entity;

use App\Repository\DeseaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeseaseRepository::class)]
class Desease
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $treatment = null;

    #[ORM\ManyToMany(targetEntity: Symptom::class, inversedBy: 'deseases')]
    private Collection $symptoms;

    #[ORM\OneToMany(mappedBy: 'desease', targetEntity: FlowerbedPlantDesease::class)]
    private Collection $flowerbedPlantDeseases;

    public function __construct()
    {
        $this->symptoms = new ArrayCollection();
        $this->flowerbedPlantDeseases = new ArrayCollection();
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

    public function getTreatment(): ?string
    {
        return $this->treatment;
    }

    public function setTreatment(string $treatment): self
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * @return Collection<int, Symptom>
     */
    public function getSymptoms(): Collection
    {
        return $this->symptoms;
    }

    public function addSymptom(Symptom $symptom): self
    {
        if (!$this->symptoms->contains($symptom)) {
            $this->symptoms->add($symptom);
        }

        return $this;
    }

    public function removeSymptom(Symptom $symptom): self
    {
        $this->symptoms->removeElement($symptom);

        return $this;
    }

    /**
     * @return Collection<int, FlowerbedPlantDesease>
     */
    public function getFlowerbedPlantDeseases(): Collection
    {
        return $this->flowerbedPlantDeseases;
    }

    public function addFlowerbedPlantDesease(FlowerbedPlantDesease $flowerbedPlantDesease): self
    {
        if (!$this->flowerbedPlantDeseases->contains($flowerbedPlantDesease)) {
            $this->flowerbedPlantDeseases->add($flowerbedPlantDesease);
            $flowerbedPlantDesease->setDesease($this);
        }

        return $this;
    }

    public function removeFlowerbedPlantDesease(FlowerbedPlantDesease $flowerbedPlantDesease): self
    {
        if ($this->flowerbedPlantDeseases->removeElement($flowerbedPlantDesease)) {
            // set the owning side to null (unless already changed)
            if ($flowerbedPlantDesease->getDesease() === $this) {
                $flowerbedPlantDesease->setDesease(null);
            }
        }

        return $this;
    }
}
