<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="car_price")
 * @ORM\Entity(repositoryClass="App\Repository\CarPriceRepository")
 */
class CarPrice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CarDay", inversedBy="carPrices", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="car_day_id", referencedColumnName="id", nullable=false)
     * @Serializer\SerializedName("carDay")
     */
    private $carDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="carPrices", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id", nullable=false)
     */
    private $car;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     * @return CarPrice
     */
    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return CarDay|null
     */
    public function getCarDay(): ?CarDay
    {
        return $this->carDay;
    }

    /**
     * @param CarDay|null $carDay
     * @return CarPrice
     */
    public function setCarDay(?CarDay $carDay): self
    {
        $this->carDay = $carDay;

        return $this;
    }

    /**
     * @return Car|null
     */
    public function getCar(): ?Car
    {
        return $this->car;
    }

    /**
     * @param Car|null $car
     * @return CarPrice
     */
    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }
}
