<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RentalRepository")
 */
class Rental
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Serializer\SerializedName("startDate")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Assert\GreaterThan(propertyPath="startDate")
     * @Serializer\SerializedName("endDate")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="rentals", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id", nullable=false)
     */
    private $car;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $price;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeInterface $startDate
     * @return Rental
     */
    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface $endDate
     * @return Rental
     */
    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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
     * @return Rental
     */
    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Rental
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
