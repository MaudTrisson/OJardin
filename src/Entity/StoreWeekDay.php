<?php

namespace App\Entity;

use App\Repository\StoreWeekDayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoreWeekDayRepository::class)]
class StoreWeekDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $open_hours = null;

    #[ORM\ManyToOne(inversedBy: 'storeWeekDays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Store $store = null;

    #[ORM\ManyToOne(inversedBy: 'storeWeekDays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WeekDay $week_day = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenHours(): string
    {
        return $this->open_hours;
    }

    public function setOpenHours(?array $open_hours): self
    {
        $this->open_hours = $open_hours;

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

    public function getWeekDay(): ?WeekDay
    {
        return $this->week_day;
    }

    public function setWeekDay(?WeekDay $week_day): self
    {
        $this->week_day = $week_day;

        return $this;
    }
}
