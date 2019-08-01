<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriceRepository")
 */
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $minDay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxDay;

    /**
     * @ORM\Column(type="float")
     */
    private $pricePerDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="prices", cascade={"persist", "merge"})
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
     * @return int|null
     */
    public function getMinDay(): ?int
    {
        return $this->minDay;
    }

    /**
     * @param int $minDay
     * @return Price
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
     * @return Price
     */
    public function setMaxDay(?int $maxDay): self
    {
        $this->maxDay = $maxDay;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPricePerDay(): ?float
    {
        return $this->pricePerDay;
    }

    /**
     * @param float $pricePerDay
     * @return Price
     */
    public function setPricePerDay(float $pricePerDay): self
    {
        $this->pricePerDay = $pricePerDay;

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
     * @return Price
     */
    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }
}
