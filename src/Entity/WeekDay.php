<?php

namespace App\Entity;

use App\Repository\WeekDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeekDayRepository::class)]
class WeekDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'week_day', targetEntity: StoreWeekDay::class)]
    private Collection $storeWeekDays;

    public function __construct()
    {
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
            $storeWeekDay->setWeekDay($this);
        }

        return $this;
    }

    public function removeStoreWeekDay(StoreWeekDay $storeWeekDay): self
    {
        if ($this->storeWeekDays->removeElement($storeWeekDay)) {
            // set the owning side to null (unless already changed)
            if ($storeWeekDay->getWeekDay() === $this) {
                $storeWeekDay->setWeekDay(null);
            }
        }

        return $this;
    }

}
