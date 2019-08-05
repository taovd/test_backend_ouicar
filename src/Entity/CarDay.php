<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="car_day")
 * @ORM\Entity(repositoryClass="App\Repository\CarDayRepository")
 */
class CarDay
{
    const PRICE_DAY_1 = 1;
    const PRICE_DAY_3 = 2;
    const PRICE_DAY_7 = 3;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="min_day", type="integer")
     * @Serializer\SerializedName("minDay")
     */
    private $minDay;

    /**
     * @ORM\Column(name="max_day", type="integer", nullable=true)
     * @Serializer\SerializedName("maxDay")
     */
    private $maxDay;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getMinDay(): ?int
    {
        return $this->minDay;
    }

    /**
     * @param int $minDay
     * @return CarDay
     */
    public function setMinDay(int $minDay): self
    {
        $this->minDay = $minDay;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxDay(): ?int
    {
        return $this->maxDay;
    }

    /**
     * @param int|null $maxDay
     * @return CarDay
     */
    public function setMaxDay(?int $maxDay): self
    {
        $this->maxDay = $maxDay;

        return $this;
    }
}
