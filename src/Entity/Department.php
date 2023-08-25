<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'departments', targetEntity: Garden::class)]
    private Collection $gardens;

    #[ORM\ManyToMany(targetEntity: Advice::class, mappedBy: 'departments')]
    private Collection $advice;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    public function __construct()
    {
        $this->gardens = new ArrayCollection();
        $this->advice = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    /**
     * @return Collection<int, Garden>
     */
    public function getGardens(): Collection
    {
        return $this->gardens;
    }

    public function addGarden(Garden $garden): self
    {
        if (!$this->gardens->contains($garden)) {
            $this->gardens->add($garden);
            $garden->setDepartments($this);
        }

        return $this;
    }

    public function removeGarden(Garden $garden): self
    {
        if ($this->gardens->removeElement($garden)) {
            // set the owning side to null (unless already changed)
            if ($garden->getDepartments() === $this) {
                $garden->getDepartments(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Advice>
     */
    public function getAdvice(): Collection
    {
        return $this->advice;
    }

    public function addAdvice(Advice $advice): self
    {
        if (!$this->advice->contains($advice)) {
            $this->advice->add($advice);
            $advice->addDepartment($this);
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): self
    {
        if ($this->advice->removeElement($advice)) {
            $advice->removeDepartment($this);
        }

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
